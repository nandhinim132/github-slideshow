<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/config.php';

function start_session_if_needed(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function generate_csrf_token(): string {
    start_session_if_needed();
    if (empty($_SESSION[CSRF_TOKEN_KEY])) {
        $_SESSION[CSRF_TOKEN_KEY] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_KEY];
}

function verify_csrf_token(?string $token): void {
    start_session_if_needed();
    if (!isset($_SESSION[CSRF_TOKEN_KEY]) || !hash_equals($_SESSION[CSRF_TOKEN_KEY], (string)$token)) {
        http_response_code(400);
        exit('Invalid CSRF token');
    }
}

function hash_password(string $password): string {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verify_password(string $password, string $hash): bool {
    return password_verify($password, $hash);
}

function login(string $username, string $password): bool {
    $user = db_fetch('SELECT id, username, password_hash, role, name FROM users WHERE username = ?', [$username]);
    if ($user && verify_password($password, $user['password_hash'])) {
        start_session_if_needed();
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
            'name' => $user['name'] ?? $user['username'],
        ];
        return true;
    }
    return false;
}

function logout(): void {
    start_session_if_needed();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function require_login(): void {
    start_session_if_needed();
    if (empty($_SESSION['user'])) {
        header('Location: /login.php');
        exit;
    }
}

function current_user(): ?array {
    start_session_if_needed();
    return $_SESSION['user'] ?? null;
}

function require_role(array $roles): void {
    require_login();
    $user = current_user();
    if (!$user || !in_array($user['role'], $roles, true)) {
        http_response_code(403);
        exit('Forbidden');
    }
}

?>

