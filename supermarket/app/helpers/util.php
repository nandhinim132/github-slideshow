<?php

function sanitize(?string $value): string {
	return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void {
	header('Location: ' . $path);
	exit;
}

function inr(float $amount): string {
	return '₹ ' . number_format($amount, 2);
}

function startSessionIfNeeded(): void {
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
}

function getCsrfToken(): string {
	startSessionIfNeeded();
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}
	return $_SESSION['csrf_token'];
}

function verifyCsrfToken(?string $token): bool {
	startSessionIfNeeded();
	return is_string($token) && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}