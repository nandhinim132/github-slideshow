<?php
require __DIR__ . '/../../../app/config/bootstrap.php';
if (!hasRole([ROLE_ADMIN, ROLE_STOCKER])) { http_response_code(403); exit('Forbidden'); }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect(APP_URL_BASE . '/modules/categories/index.php'); }
if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) { http_response_code(400); exit('Invalid CSRF token'); }

$id = (int)($_POST['id'] ?? 0);
if ($id > 0) {
	try {
		dbQuery('DELETE FROM categories WHERE id = ? LIMIT 1', [$id]);
	} catch (Throwable $e) {
		// ignore and redirect
	}
}
redirect(APP_URL_BASE . '/modules/categories/index.php');