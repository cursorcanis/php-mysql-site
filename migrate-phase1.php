<?php
// ============================================================
//  ALEA LAB VAULT — migrate-phase1.php  (ONE-TIME USE)
//  Adds the `file_type` column required by Phase 1.
//  Upload to /lab, visit http://lab.alfredoalea.com/migrate-phase1.php
//  then DELETE THIS FILE. Safe to run more than once.
// ============================================================

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config.php';

echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'>
<title>Alea Lab Vault — Phase 1 Migration</title>
<style>
  body { font-family: monospace; background:#080b10; color:#c8d8e8; padding:20px; line-height:1.6; }
  h1 { color:#00e5ff; font-size:1.4rem; border-bottom:1px solid #1e2a3a; padding-bottom:10px; }
  .ok { color:#34d399; font-weight:bold; }
  .bad { color:#ff3d71; font-weight:bold; }
  .info { color:#ffe066; }
  pre { background:#0e1420; border:1px solid #1e2a3a; padding:10px; border-radius:4px; color:#f472b6; }
</style></head><body>
<h1>Phase 1 Migration — add images.file_type</h1>";

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $db = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    echo "<span class='ok'>&#10003;</span> Connected to <code>" . htmlspecialchars(DB_NAME) . "</code>.<br><br>";

    // 1) Does the column already exist? (works on both MySQL and MariaDB)
    $stmt = $db->prepare(
        "SELECT COUNT(*) FROM information_schema.COLUMNS
          WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'images' AND COLUMN_NAME = 'file_type'"
    );
    $stmt->execute([DB_NAME]);
    $exists = (int) $stmt->fetchColumn();

    if ($exists) {
        echo "<span class='info'>i</span> Column <code>file_type</code> already exists &mdash; nothing to do.<br>";
    } else {
        echo "Adding column <code>file_type</code>&hellip;<br>";
        $db->exec(
            "ALTER TABLE `images`
             ADD COLUMN `file_type` ENUM('image','document','file')
             NOT NULL DEFAULT 'image' AFTER `mime_type`"
        );
        // Backfill existing rows by their MIME type, just to be tidy.
        $db->exec("UPDATE `images` SET `file_type` = 'document'
                   WHERE `mime_type` = 'text/plain' OR `mime_type` LIKE 'application/%'");
        echo "<span class='ok'>&#10003;</span> Column added and existing rows backfilled.<br>";
    }

    // 2) Verify final state and show the resulting schema.
    echo "<br>Current <code>images</code> columns:<br><pre>";
    foreach ($db->query("SHOW COLUMNS FROM `images`") as $col) {
        $flag = ($col['Field'] === 'file_type') ? '  <-- Phase 1' : '';
        echo str_pad($col['Field'], 18) . $col['Type'] . $flag . "\n";
    }
    echo "</pre>";

    echo "<p class='ok'>&#10003; Migration complete.</p>";
    echo "<p class='bad'>Now DELETE migrate-phase1.php (and debug.php) from the server.</p>";

} catch (PDOException $e) {
    echo "<span class='bad'>&#10007; Migration failed:</span><pre>"
       . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<p class='info'>Fix the error above and re-run. This script is safe to run again.</p>";
}

echo "</body></html>";
