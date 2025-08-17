<?php

class AuthController
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST[$this->config['csrf_key']] ?? null;
            if (!verifyCsrfToken($this->config['csrf_key'], $token)) {
                $error = 'Invalid CSRF token.';
                View::render('auth/login', compact('error'));
                return;
            }
            $username = trim((string) request_param('username', ''));
            $password = (string) request_param('password', '');
            if (Auth::login($username, $password)) {
                redirect('dashboard');
                return;
            }
            $error = 'Invalid credentials';
            View::render('auth/login', compact('error'));
            return;
        }
        View::render('auth/login');
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('login');
    }

    public function changePassword(): void
    {
        require_login();
        $message = null;
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST[$this->config['csrf_key']] ?? null;
            if (!verifyCsrfToken($this->config['csrf_key'], $token)) {
                $error = 'Invalid CSRF token.';
                View::render('auth/change_password', compact('error', 'message'));
                return;
            }
            $current = (string) request_param('current_password');
            $new = (string) request_param('new_password');
            $confirm = (string) request_param('confirm_password');
            $user = current_user();
            if (!$user) {
                redirect('login');
                return;
            }
            $dbUser = Database::fetch('SELECT * FROM users WHERE id = :id', ['id' => $user['id']]);
            if (!$dbUser || !password_verify($current, $dbUser['password_hash'])) {
                $error = 'Current password is incorrect';
            } elseif ($new !== $confirm || strlen($new) < 6) {
                $error = 'New passwords do not match or too short';
            } else {
                $hash = password_hash($new, PASSWORD_BCRYPT);
                Database::query('UPDATE users SET password_hash = :p WHERE id = :id', ['p' => $hash, 'id' => $user['id']]);
                $message = 'Password changed successfully';
            }
        }
        View::render('auth/change_password', compact('error', 'message'));
    }
}