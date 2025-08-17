<?php

class CategoryController
{
    public function index(): void
    {
        require_login();
        $categories = Database::fetchAll('SELECT * FROM categories ORDER BY name');
        View::render('categories/index', compact('categories'));
    }

    public function create(): void
    {
        Auth::requireRole(['admin']);
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim((string) request_param('name'));
            try {
                Database::insert('INSERT INTO categories (name, created_at) VALUES (:n,:c)', [
                    'n' => $name,
                    'c' => date('c'),
                ]);
                redirect('categories');
                return;
            } catch (Throwable $e) {
                $error = 'Error: ' . $e->getMessage();
            }
        }
        View::render('categories/create', compact('error'));
    }

    public function edit(): void
    {
        Auth::requireRole(['admin']);
        $id = (int) request_param('id', 0);
        $category = Database::fetch('SELECT * FROM categories WHERE id = :id', ['id' => $id]);
        if (!$category) {
            redirect('categories');
            return;
        }
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim((string) request_param('name'));
            Database::query('UPDATE categories SET name = :n WHERE id = :id', ['n' => $name, 'id' => $id]);
            redirect('categories');
            return;
        }
        View::render('categories/edit', compact('category', 'error'));
    }

    public function delete(): void
    {
        Auth::requireRole(['admin']);
        $id = (int) request_param('id', 0);
        Database::query('DELETE FROM categories WHERE id = :id', ['id' => $id]);
        redirect('categories');
    }
}