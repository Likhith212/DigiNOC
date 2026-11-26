<?php
declare(strict_types=1);

function e(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path): never {
    header('Location: ' . $path);
    exit;
}

function flash(string $key, ?string $message = null): ?string {
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }
    $value = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $value;
}

function audit_log(PDO $pdo, int $userId, string $role, string $action, ?int $recordId = null, ?string $details = null): void {
    $statement = $pdo->prepare(
        'INSERT INTO audit_logs (user_id, user_role, action, record_id, details, ip_address)
         VALUES (?, ?, ?, ?, ?, ?)'
    );
    $statement->execute([
        $userId,
        $role,
        $action,
        $recordId,
        $details,
        $_SERVER['REMOTE_ADDR'] ?? null,
    ]);
}

function notify(PDO $pdo, int $userId, string $title, string $message, ?string $link = null): void {
    $statement = $pdo->prepare(
        'INSERT INTO notifications (user_id, title, message, link) VALUES (?, ?, ?, ?)'
    );
    $statement->execute([$userId, $title, $message, $link]);
}

function certificate_id(): string {
    return 'CLR-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(5)));
}
