<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path): void {
    header('Location: ' . $path);
    exit;
}

function money(float $amount): string {
    global $CURRENCY_SYMBOL;
    return $CURRENCY_SYMBOL . number_format($amount, 2);
}

function input(array $source, string $key, $default = null) {
    return $source[$key] ?? $default;
}

function is_post(): bool { return $_SERVER['REQUEST_METHOD'] === 'POST'; }
function is_get(): bool { return $_SERVER['REQUEST_METHOD'] === 'GET'; }

?>

