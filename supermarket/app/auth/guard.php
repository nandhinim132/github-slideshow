<?php
require_once __DIR__ . '/../config/bootstrap.php';

$publicPaths = [
	APP_URL_BASE . '/login.php',
];

$currentPath = $_SERVER['PHP_SELF'] ?? '';
if (!in_array($currentPath, $publicPaths, true)) {
	if (!isAuthenticated()) {
		redirect(APP_URL_BASE . '/login.php');
	}
}