<?php

class UserController
{
    public function index(): void
    {
        Auth::requireRole(['admin']);
        $users = Database::fetchAll('SELECT id, username, name, role, created_at FROM users ORDER BY id DESC');
        View::render('users/index', compact('users'));
    }

    public function create(): void
    {
        Auth::requireRole(['admin']);
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim((string) request_param('username'));
            $name = trim((string) request_param('name'));
            $role = (string) request_param('role');
            $password = (string) request_param('password');
            try {
                $id = Database::insert('INSERT INTO users (username, name, role, password_hash, created_at) VALUES (:u,:n,:r,:p,:c)', [
                    'u' => $username,
                    'n' => $name,
                    'r' => $role,
                    'p' => password_hash($password, PASSWORD_BCRYPT),
                    'c' => date('c'),
                ]);
                redirect('users');
                return;
            } catch (Throwable $e) {
                $error = 'Could not create user: ' . $e->getMessage();
            }
        }
        View::render('users/create', compact('error'));
    }

    public function edit(): void
    {
        Auth::requireRole(['admin']);
        $id = (int) request_param('id', 0);
        $user = Database::fetch('SELECT * FROM users WHERE id = :id', ['id' => $id]);
        if (!$user) {
            redirect('users');
            return;
        }
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim((string) request_param('name'));
            $role = (string) request_param('role');
            Database::query('UPDATE users SET name = :n, role = :r WHERE id = :id', ['n' => $name, 'r' => $role, 'id' => $id]);
            redirect('users');
            return;
        }
        View::render('users/edit', compact('user', 'error'));
    }

    public function delete(): void
    {
        Auth::requireRole(['admin']);
        $id = (int) request_param('id', 0);
        Database::query('DELETE FROM users WHERE id = :id', ['id' => $id]);
        redirect('users');
    }
}