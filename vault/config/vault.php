<?php

return [

    // App display title
    'title' => env('APP_NAME', 'Alea Lab Vault'),

    // Single-user login (credentials live in .env)
    'user'      => env('VAULT_USER', 'alfredo'),
    'pass_hash' => env('VAULT_PASS_HASH', ''),

    // Upload constraints (ported from the original config.php)
    'max_file_size' => 100 * 1024 * 1024, // 100 MB

    'allowed_mime' => [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp',
        'text/plain',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/msword',
    ],

    'allowed_ext' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'txt', 'docx', 'doc'],

    // Disk (private) where uploads are stored, served only via controller routes
    'disk' => 'local',
    'upload_path' => 'uploads',
];
