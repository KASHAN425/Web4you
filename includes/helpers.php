<?php
require_once __DIR__ . '/../config.php';

function json_response($data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function sanitize(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function current_user_id(): ?int
{
    return $_SESSION['user_id'] ?? null;
}

function is_admin(): bool
{
    return isset($_SESSION['admin_id']);
}
?>
