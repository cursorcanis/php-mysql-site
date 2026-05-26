<?php
// ============================================================
//  ALEA LAB VAULT — config.php
//  Edit these values before uploading to IONOS
// ============================================================

// --- Database (get these from IONOS > Hosting > Databases) ---
define('DB_HOST', 'db5xxxxxxx.hosting-data.io'); // your IONOS DB host
define('DB_NAME', 'dbs_xxxxxxx');                // your database name
define('DB_USER', 'dbu_xxxxxxx');                // your DB username
define('DB_PASS', 'YOUR_DB_PASSWORD');           // your DB password

// --- Login credentials (single user) ---
// Generate a hash: php -r "echo password_hash('yourpassword', PASSWORD_BCRYPT);"
// Or replace the hash below after generating it
define('VAULT_USER', 'alfredo');
define('VAULT_PASS_HASH', '$2y$12$Uz7wePVw/vo0rDYrKYRTAOGpVhFDG12YXaPKsdIN88y3VggfSe3VS'); // CHANGE THIS

// --- Upload settings ---
define('UPLOAD_DIR', __DIR__ . '/uploads/');     // absolute path to uploads folder
define('MAX_FILE_SIZE', 10 * 1024 * 1024);       // 10 MB max per image
define('ALLOWED_MIME', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('ALLOWED_EXT',  ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// --- Session ---
define('SESSION_NAME', 'vault_session');
define('SESSION_LIFETIME', 3600); // 1 hour

// --- App ---
define('APP_TITLE', 'Alea Lab Vault');
