<?php

class ProductController
{
    public function index(): void
    {
        require_login();
        $products = Database::fetchAll('SELECT p.*, c.name AS category_name, s.name AS supplier_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN suppliers s ON p.supplier_id = s.id
            ORDER BY p.id DESC');
        View::render('products/index', compact('products'));
    }

    public function create(): void
    {
        Auth::requireRole(['admin']);
        $categories = Database::fetchAll('SELECT * FROM categories ORDER BY name');
        $suppliers = Database::fetchAll('SELECT * FROM suppliers ORDER BY name');
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim((string) request_param('name')),
                'category_id' => (int) request_param('category_id'),
                'supplier_id' => (int) request_param('supplier_id'),
                'price' => (float) request_param('price'),
                'mrp' => (float) request_param('mrp'),
                'quantity' => (int) request_param('quantity'),
                'expiry_date' => request_param('expiry_date'),
                'barcode' => trim((string) request_param('barcode')),
                'stock_threshold' => (int) request_param('stock_threshold', 5),
            ];
            try {
                Database::insert('INSERT INTO products (name, category_id, supplier_id, price, mrp, quantity, expiry_date, barcode, stock_threshold, created_at) VALUES (:name,:category_id,:supplier_id,:price,:mrp,:quantity,:expiry_date,:barcode,:stock_threshold,:created_at)', [
                    ...$data,
                    'created_at' => date('c'),
                ]);
                redirect('products');
                return;
            } catch (Throwable $e) {
                $error = 'Error: ' . $e->getMessage();
            }
        }
        View::render('products/create', compact('categories', 'suppliers', 'error'));
    }

    public function edit(): void
    {
        Auth::requireRole(['admin']);
        $id = (int) request_param('id');
        $product = Database::fetch('SELECT * FROM products WHERE id = :id', ['id' => $id]);
        if (!$product) {
            redirect('products');
            return;
        }
        $categories = Database::fetchAll('SELECT * FROM categories ORDER BY name');
        $suppliers = Database::fetchAll('SELECT * FROM suppliers ORDER BY name');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim((string) request_param('name')),
                'category_id' => (int) request_param('category_id'),
                'supplier_id' => (int) request_param('supplier_id'),
                'price' => (float) request_param('price'),
                'mrp' => (float) request_param('mrp'),
                'quantity' => (int) request_param('quantity'),
                'expiry_date' => request_param('expiry_date'),
                'barcode' => trim((string) request_param('barcode')),
                'stock_threshold' => (int) request_param('stock_threshold', 5),
            ];
            Database::query('UPDATE products SET name = :name, category_id = :category_id, supplier_id = :supplier_id, price = :price, mrp = :mrp, quantity = :quantity, expiry_date = :expiry_date, barcode = :barcode, stock_threshold = :stock_threshold WHERE id = :id', [
                ...$data,
                'id' => $id,
            ]);
            redirect('products');
            return;
        }
        View::render('products/edit', compact('product', 'categories', 'suppliers'));
    }

    public function delete(): void
    {
        Auth::requireRole(['admin']);
        $id = (int) request_param('id');
        Database::query('DELETE FROM products WHERE id = :id', ['id' => $id]);
        redirect('products');
    }
}