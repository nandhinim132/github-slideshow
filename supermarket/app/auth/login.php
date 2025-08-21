<?php
require_once __DIR__ . '/../config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	redirect(APP_URL_BASE . '/login.php');
}

$token = $_POST['csrf_token'] ?? null;
if (!verifyCsrfToken($token)) {
	http_response_code(400);
	exit('Invalid CSRF token');
}

$username = trim((string)($_POST['username'] ?? ''));
$password = (string)($_POST['password'] ?? '');

if ($username === '' || $password === '') {
	redirect(APP_URL_BASE . '/login.php');
}

if (loginUser($username, $password)) {
	redirect(APP_URL_BASE . '/index.php');
}

startSessionIfNeeded();
$_SESSION['flash_error'] = 'Invalid credentials or inactive account';
redirect(APP_URL_BASE . '/login.php');