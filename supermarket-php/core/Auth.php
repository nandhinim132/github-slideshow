<?php

class Auth
{
    public static function login(string $username, string $password): bool
    {
        $user = Database::fetch('SELECT * FROM users WHERE username = :u', ['u' => $username]);
        if (!$user) {
            return false;
        }
        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'name' => $user['name'],
            'role' => $user['role'],
        ];
        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
    }

    public static function requireRole(array $roles): void
    {
        $user = current_user();
        if (!$user || !in_array($user['role'], $roles, true)) {
            redirect('login');
        }
    }
}