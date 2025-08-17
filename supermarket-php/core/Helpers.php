<?php

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $route): void
{
    if (strpos($route, 'http') !== 0) {
        $route = '/index.php?r=' . ltrim($route, '/');
    }
    header('Location: ' . $route);
    exit;
}

function base_url(string $route = ''): string
{
    return '/index.php' . ($route ? ('?r=' . ltrim($route, '/')) : '');
}

function generateCsrfToken(string $key): string
{
    if (empty($_SESSION[$key])) {
        $_SESSION[$key] = bin2hex(random_bytes(16));
    }
    return $_SESSION[$key];
}

function verifyCsrfToken(string $key, ?string $token): bool
{
    return isset($_SESSION[$key]) && is_string($token) && hash_equals($_SESSION[$key], $token);
}

function format_inr(float $amount, bool $withSymbol = true): string
{
    $negative = $amount < 0;
    $amount = abs($amount);
    $parts = explode('.', number_format($amount, 2, '.', ''));
    $integer = $parts[0];
    $decimal = $parts[1] ?? '00';

    $len = strlen($integer);
    if ($len > 3) {
        $last3 = substr($integer, -3);
        $rest = substr($integer, 0, $len - 3);
        $rest = preg_replace('/\B(?=(?:\d{2})+(?!\d))/', ',', $rest);
        $formatted = $rest . ',' . $last3;
    } else {
        $formatted = $integer;
    }

    $result = ($withSymbol ? '₹ ' : '') . ($negative ? '-' : '') . $formatted . '.' . $decimal;
    return $result;
}

function request_param(string $key, $default = null)
{
    return $_POST[$key] ?? $_GET[$key] ?? $default;
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_login(): void
{
    if (!current_user()) {
        redirect('login');
    }
}