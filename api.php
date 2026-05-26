<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_auth();

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$db     = get_db();

function json_out(array $data): void {
    echo json_encode($data);
    exit;
}

// Verify CSRF for all POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        json_out(['ok' => false, 'error' => 'Invalid token']);
    }
}

switch ($action) {

    // --- Get all tags ---
    case 'get_tags':
        $tags = $db->query('SELECT * FROM tags ORDER BY name')->fetchAll();
        json_out(['ok' => true, 'tags' => $tags]);

    // --- Create a new tag ---
    case 'create_tag':
        $name  = trim($_POST['name'] ?? '');
        $color = trim($_POST['color'] ?? '#00e5ff');
        if (!$name) json_out(['ok' => false, 'error' => 'Name required']);
        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) $color = '#00e5ff';
        try {
            $stmt = $db->prepare('INSERT INTO tags (name, color) VALUES (?, ?)');
            $stmt->execute([$name, $color]);
            json_out(['ok' => true, 'id' => $db->lastInsertId(), 'name' => $name, 'color' => $color]);
        } catch (PDOException $e) {
            json_out(['ok' => false, 'error' => 'Tag already exists']);
        }

    // --- Delete a tag ---
    case 'delete_tag':
        $id = (int)($_POST['tag_id'] ?? 0);
        $db->prepare('DELETE FROM tags WHERE id = ?')->execute([$id]);
        json_out(['ok' => true]);

    // --- Set tags for an image (replaces all) ---
    case 'set_image_tags':
        $imageId = (int)($_POST['image_id'] ?? 0);
        $tagIds  = array_map('intval', json_decode($_POST['tag_ids'] ?? '[]', true) ?: []);
        $db->prepare('DELETE FROM image_tags WHERE image_id = ?')->execute([$imageId]);
        $stmt = $db->prepare('INSERT IGNORE INTO image_tags (image_id, tag_id) VALUES (?, ?)');
        foreach ($tagIds as $tid) {
            $stmt->execute([$imageId, $tid]);
        }
        json_out(['ok' => true]);

    // --- Update image title + notes ---
    case 'update_image':
        $id    = (int)($_POST['image_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $notes = trim($_POST['notes'] ?? '');
        $db->prepare('UPDATE images SET title = ?, notes = ? WHERE id = ?')
           ->execute([$title ?: null, $notes ?: null, $id]);
        json_out(['ok' => true]);

    // --- Generate / get share token ---
    case 'get_share_token':
        $id   = (int)($_POST['image_id'] ?? 0);
        $row  = $db->prepare('SELECT share_token, filename FROM images WHERE id = ?');
        $row->execute([$id]);
        $img  = $row->fetch();
        if (!$img) json_out(['ok' => false, 'error' => 'Not found']);
        if (!$img['share_token']) {
            $token = bin2hex(random_bytes(24));
            $db->prepare('UPDATE images SET share_token = ? WHERE id = ?')->execute([$token, $id]);
        } else {
            $token = $img['share_token'];
        }
        json_out(['ok' => true, 'token' => $token]);

    // --- Revoke share token ---
    case 'revoke_share':
        $id = (int)($_POST['image_id'] ?? 0);
        $db->prepare('UPDATE images SET share_token = NULL WHERE id = ?')->execute([$id]);
        json_out(['ok' => true]);

    // --- Search images ---
    case 'search':
        $q     = '%' . trim($_GET['q'] ?? '') . '%';
        $tagId = (int)($_GET['tag_id'] ?? 0);
        if ($tagId) {
            $stmt = $db->prepare(
                'SELECT i.* FROM images i
                 JOIN image_tags it ON it.image_id = i.id
                 WHERE it.tag_id = ? AND (i.title LIKE ? OR i.original_name LIKE ? OR i.notes LIKE ?)
                 ORDER BY i.uploaded_at DESC'
            );
            $stmt->execute([$tagId, $q, $q, $q]);
        } else {
            $stmt = $db->prepare(
                'SELECT * FROM images WHERE title LIKE ? OR original_name LIKE ? OR notes LIKE ?
                 ORDER BY uploaded_at DESC'
            );
            $stmt->execute([$q, $q, $q]);
        }
        $images = $stmt->fetchAll();
        // Attach tags to each image
        foreach ($images as &$img) {
            $ts = $db->prepare(
                'SELECT t.* FROM tags t JOIN image_tags it ON it.tag_id = t.id WHERE it.image_id = ?'
            );
            $ts->execute([$img['id']]);
            $img['tags'] = $ts->fetchAll();
        }
        json_out(['ok' => true, 'images' => $images]);

    default:
        json_out(['ok' => false, 'error' => 'Unknown action']);
}
