<?php
require_once __DIR__ . '/config.php';

ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

session_name(SESSION_NAME);
session_start();

function is_logged_in(): bool {
    return isset($_SESSION['vault_auth']) && $_SESSION['vault_auth'] === true;
}

function login_user(): void {
    session_regenerate_id(true);
    $_SESSION['vault_auth'] = true;
    $_SESSION['vault_time'] = time();
}

function logout_user(): void {
    $_SESSION = [];
    session_destroy();
}

function require_auth(): void {
    if (!is_logged_in()) {
        header('Location: index.php');
        exit;
    }
    // Session timeout
    if (isset($_SESSION['vault_time']) && (time() - $_SESSION['vault_time']) > SESSION_LIFETIME) {
        logout_user();
        header('Location: index.php?timeout=1');
        exit;
    }
    $_SESSION['vault_time'] = time(); // refresh
}

function get_db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
