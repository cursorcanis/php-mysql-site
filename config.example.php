<?php
// ============================================================
//  ALEA LAB VAULT — config.example.php
//  Copy this file to config.php and fill in real values.
//  config.php is gitignored and must NEVER be committed.
// ============================================================

// --- Database (get these from IONOS > Hosting > Databases) ---
define('DB_HOST', 'your-db-host.hosting-data.io');
define('DB_NAME', 'your_db_name');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your-db-password');           // paste the password you set in IONOS

// --- Login credentials (single user) ---
// Generate a hash: php -r "echo password_hash('yourpassword', PASSWORD_BCRYPT);"
// Or replace the hash below after generating it
define('VAULT_USER', 'your-username');
define('VAULT_PASS_HASH', '$2y$10$replace.with.your.own.bcrypt.hash.value.here'); // CHANGE THIS

// --- Upload settings ---
define('UPLOAD_DIR', __DIR__ . '/uploads/');     // absolute path to uploads folder
define('MAX_FILE_SIZE', 100 * 1024 * 1024);      // 100 MB max per file
define('ALLOWED_MIME', [
    'image/jpeg', 'image/png', 'image/gif', 'image/webp',
    'text/plain',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/msword'
]);
define('ALLOWED_EXT',  ['jpg', 'jpeg', 'png', 'gif', 'webp', 'txt', 'docx', 'doc']);

// --- Session ---
define('SESSION_NAME', 'vault_session');
define('SESSION_LIFETIME', 3600); // 1 hour

// --- App ---
define('APP_TITLE', 'Alea Lab Vault');

// Helper function to determine file type
function get_file_type($mime, $ext) {
    if (strpos($mime, 'image/') === 0) return 'image';
    if ($mime === 'text/plain') return 'document';
    if (strpos($mime, 'application/') === 0) return 'document';
    return 'file';
}
