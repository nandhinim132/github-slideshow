<?php

class EmployeeController
{
    public function index(): void
    {
        require_login();
        $employees = Database::fetchAll('SELECT * FROM employees ORDER BY id DESC');
        View::render('employees/index', compact('employees'));
    }

    public function create(): void
    {
        Auth::requireRole(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim((string) request_param('name')),
                'role' => (string) request_param('role'),
                'salary' => (float) request_param('salary'),
                'phone' => trim((string) request_param('phone')),
            ];
            Database::insert('INSERT INTO employees (name, role, salary, phone, created_at) VALUES (:name,:role,:salary,:phone,:created_at)', [
                ...$data,
                'created_at' => date('c'),
            ]);
            redirect('employees');
            return;
        }
        View::render('employees/create');
    }

    public function edit(): void
    {
        Auth::requireRole(['admin']);
        $id = (int) request_param('id');
        $employee = Database::fetch('SELECT * FROM employees WHERE id = :id', ['id' => $id]);
        if (!$employee) {
            redirect('employees');
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim((string) request_param('name')),
                'role' => (string) request_param('role'),
                'salary' => (float) request_param('salary'),
                'phone' => trim((string) request_param('phone')),
            ];
            Database::query('UPDATE employees SET name = :name, role = :role, salary = :salary, phone = :phone WHERE id = :id', [
                ...$data,
                'id' => $id,
            ]);
            redirect('employees');
            return;
        }
        View::render('employees/edit', compact('employee'));
    }

    public function delete(): void
    {
        Auth::requireRole(['admin']);
        $id = (int) request_param('id');
        Database::query('DELETE FROM employees WHERE id = :id', ['id' => $id]);
        redirect('employees');
    }
}