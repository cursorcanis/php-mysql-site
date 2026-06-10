<?php
// ============================================================
//  ALEA LAB VAULT — debug.php
//  Upload this file to your /lab folder on IONOS and visit:
//  http://lab.alfredoalea.com/debug.php
//  DELETE THIS FILE AFTER DIAGNOSING!
// ============================================================

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Alea Lab Vault — Diagnostics</title>
    <style>
        body { font-family: monospace; background: #080b10; color: #c8d8e8; padding: 20px; line-height: 1.6; }
        h1 { color: #00e5ff; font-size: 1.5rem; border-bottom: 1px solid #1e2a3a; padding-bottom: 10px; }
        .section { margin-bottom: 20px; background: #0e1420; border: 1px solid #1e2a3a; padding: 15px; border-radius: 6px; }
        .success { color: #34d399; font-weight: bold; }
        .danger { color: #ff3d71; font-weight: bold; }
        .info { color: #ffe066; }
        pre { background: #080b10; border: 1px solid #1e2a3a; padding: 10px; border-radius: 4px; overflow-x: auto; color: #f472b6; }
    </style>
</head>
<body>
<h1>Alea Lab Vault Diagnostics</h1>";

// --- Section 1: config.php Check ---
echo "<div class='section'>";
echo "<h3>1. Checking config.php</h3>";
// debug.php may live in a _tools/ subfolder or sit flat next to config.php,
// so look in the same dir first, then one level up.
$configPath = file_exists(__DIR__ . '/config.php')
    ? __DIR__ . '/config.php'
    : __DIR__ . '/../config.php';
if (file_exists($configPath)) {
    echo "<span class='success'>✓</span> config.php exists (<code>" . htmlspecialchars($configPath) . "</code>).<br>";
    require_once $configPath;
    echo "DB Host: " . htmlspecialchars(DB_HOST) . "<br>";
    echo "DB Name: " . htmlspecialchars(DB_NAME) . "<br>";
    echo "DB User: " . htmlspecialchars(DB_USER) . "<br>";
    if (defined('DB_PASS')) {
        $len = strlen(DB_PASS);
        echo "DB Pass Masked: <code>" . ($len > 0 ? htmlspecialchars(DB_PASS[0] . str_repeat('*', $len - 2) . DB_PASS[$len - 1]) : 'empty') . "</code> ($len chars)<br>";
    }
    echo "Upload Dir: " . htmlspecialchars(UPLOAD_DIR) . "<br>";
} else {
    echo "<span class='danger'>✗ config.php not found!</span> Please upload config.php.<br>";
}
echo "</div>";

// --- Section 2: Environment Check ---
echo "<div class='section'>";
echo "<h3>2. Environment Check</h3>";
echo "PHP Version: " . PHP_VERSION . "<br>";
if (extension_loaded('pdo_mysql')) {
    echo "<span class='success'>✓</span> PDO MySQL extension is loaded.<br>";
} else {
    echo "<span class='danger'>✗ PDO MySQL extension is missing!</span><br>";
}
echo "</div>";

// --- Section 3: Upload Folder Check ---
echo "<div class='section'>";
echo "<h3>3. Upload Folder Check</h3>";
if (defined('UPLOAD_DIR')) {
    if (is_dir(UPLOAD_DIR)) {
        echo "<span class='success'>✓</span> Upload folder exists.<br>";
        if (is_writable(UPLOAD_DIR)) {
            echo "<span class='success'>✓</span> Upload folder is writable.<br>";
        } else {
            echo "<span class='danger'>✗ Upload folder is NOT writable!</span> (Permissions might need to be chmod 755 or 777)<br>";
        }
    } else {
        echo "<span class='info'>i</span> Upload folder does not exist yet. The app will attempt to create it on the first upload.<br>";
    }
}
echo "</div>";

// --- Section 4: Database Connection Check ---
echo "<div class='section'>";
echo "<h3>4. Database Connection Check</h3>";
if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER') && defined('DB_PASS')) {
    try {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        echo "Attempting PDO connection to: <code>mysql:host=" . htmlspecialchars(DB_HOST) . ";dbname=" . htmlspecialchars(DB_NAME) . "</code>...<br>";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        echo "<span class='success'>✓ Database connection successful!</span><br>";

        // --- Section 5: Database Schema Check ---
        echo "<h3>5. Database Schema Check</h3>";
        $tables = ['images', 'tags', 'image_tags'];
        foreach ($tables as $table) {
            try {
                $stmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
                $count = $stmt->fetchColumn();
                echo "<span class='success'>✓</span> Table <code>$table</code> exists (contains $count rows).<br>";
            } catch (PDOException $e) {
                echo "<span class='danger'>✗ Table <code>$table</code> check failed:</span> " . htmlspecialchars($e->getMessage()) . "<br>";
                echo "<div class='info'>Did you forget to run the <code>setup.sql</code> script in phpMyAdmin?</div>";
            }
        }
    } catch (PDOException $e) {
        echo "<span class='danger'>✗ Database connection failed!</span><br>";
        echo "Error message:<br>";
        echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
        echo "<h4>Troubleshooting DB Connection:</h4>";
        echo "<ul>
            <li>Ensure the hostname <code>" . htmlspecialchars(DB_HOST) . "</code> is exactly what IONOS shows under <b>Hosting &gt; Databases &gt; Hostname / Host</b>.</li>
            <li>Ensure the database name <code>" . htmlspecialchars(DB_NAME) . "</code> is correct.</li>
            <li>Ensure the username <code>" . htmlspecialchars(DB_USER) . "</code> is correct.</li>
            <li>Ensure the password you set in the IONOS database settings matches what is in <code>config.php</code>.</li>
        </ul>";
    }
} else {
    echo "<span class='danger'>✗ Database constants not defined!</span> Check config.php.<br>";
}
echo "</div>";

echo "<p style='font-size: 0.8rem; color: #4a5a6a;'>Please delete this file from your server after diagnosing the issue to keep your site secure.</p>";
echo "</body></html>";
