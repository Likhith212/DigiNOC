<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }
    session_start();
}

function is_logged_in(): bool {
    return isset($_SESSION['user']);
}

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: /CLEARX/login.php');
        exit;
    }
}

function require_role(array $roles): void {
    require_login();
    if (!in_array(current_user()['role'], $roles, true)) {
        http_response_code(403);
        exit('Access denied.');
    }
}

function base_url(): string {
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    foreach (['/student/', '/faculty/', '/hod/', '/principal/', '/admin/', '/verification/', '/config/', '/includes/'] as $marker) {
        $position = stripos($script, $marker);
        if ($position !== false) {
            return substr($script, 0, $position);
        }
    }
    $directory = rtrim(str_replace('\\', '/', dirname($script)), '/');
    return $directory === '.' || $directory === '' ? '' : $directory;
}
