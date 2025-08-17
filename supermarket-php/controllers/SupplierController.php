<?php

class SupplierController
{
    public function index(): void
    {
        require_login();
        $suppliers = Database::fetchAll('SELECT * FROM suppliers ORDER BY name');
        View::render('suppliers/index', compact('suppliers'));
    }

    public function create(): void
    {
        Auth::requireRole(['admin']);
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim((string) request_param('name')),
                'phone' => trim((string) request_param('phone')),
                'email' => trim((string) request_param('email')),
                'address' => trim((string) request_param('address')),
            ];
            try {
                Database::insert('INSERT INTO suppliers (name, phone, email, address, created_at) VALUES (:name,:phone,:email,:address,:created_at)', [
                    ...$data,
                    'created_at' => date('c'),
                ]);
                redirect('suppliers');
                return;
            } catch (Throwable $e) {
                $error = 'Error: ' . $e->getMessage();
            }
        }
        View::render('suppliers/create', compact('error'));
    }

    public function edit(): void
    {
        Auth::requireRole(['admin']);
        $id = (int) request_param('id');
        $supplier = Database::fetch('SELECT * FROM suppliers WHERE id = :id', ['id' => $id]);
        if (!$supplier) {
            redirect('suppliers');
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim((string) request_param('name')),
                'phone' => trim((string) request_param('phone')),
                'email' => trim((string) request_param('email')),
                'address' => trim((string) request_param('address')),
            ];
            Database::query('UPDATE suppliers SET name = :name, phone = :phone, email = :email, address = :address WHERE id = :id', [
                ...$data,
                'id' => $id,
            ]);
            redirect('suppliers');
            return;
        }
        View::render('suppliers/edit', compact('supplier'));
    }

    public function delete(): void
    {
        Auth::requireRole(['admin']);
        $id = (int) request_param('id');
        Database::query('DELETE FROM suppliers WHERE id = :id', ['id' => $id]);
        redirect('suppliers');
    }
}