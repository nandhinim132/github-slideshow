<?php

function isAuthenticated(): bool {
	startSessionIfNeeded();
	return isset($_SESSION['user']);
}

function currentUser(): ?array {
	startSessionIfNeeded();
	return $_SESSION['user'] ?? null;
}

function hasRole($roles): bool {
	$user = currentUser();
	if (!$user) { return false; }
	$role = $user['role'] ?? '';
	$rolesArray = is_array($roles) ? $roles : [$roles];
	return in_array($role, $rolesArray, true);
}

function getUserByUsername(string $username): ?array {
	$sql = 'SELECT id, username, password_hash, role, full_name, status FROM users WHERE username = ? LIMIT 1';
	return dbFetchOne($sql, [$username]);
}

function loginUser(string $username, string $password): bool {
	$user = getUserByUsername($username);
	if (!$user) { return false; }
	if ($user['status'] !== 'active') { return false; }
	if (!password_verify($password, $user['password_hash'])) { return false; }

	startSessionIfNeeded();
	$_SESSION['user'] = [
		'id' => (int)$user['id'],
		'username' => $user['username'],
		'role' => $user['role'],
		'full_name' => $user['full_name'] ?? $user['username'],
	];
	return true;
}

function logoutUser(): void {
	startSessionIfNeeded();
	$_SESSION = [];
	if (ini_get('session.use_cookies')) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
	}
	session_destroy();
}