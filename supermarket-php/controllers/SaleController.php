<?php

class SaleController
{
    public function index(): void
    {
        require_login();
        $sales = Database::fetchAll('SELECT s.*, u.name AS user_name FROM sales s LEFT JOIN users u ON u.id = s.user_id ORDER BY s.id DESC');
        View::render('sales/index', compact('sales'));
    }

    public function create(): void
    {
        require_login();
        $products = Database::fetchAll('SELECT id, name, price, quantity FROM products ORDER BY name');
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = $_POST['items'] ?? [];
            $discount = (float) request_param('discount', 0);
            $tax = (float) request_param('tax', 0);
            $customer_name = trim((string) request_param('customer_name'));
            if (!is_array($items) || count($items) === 0) {
                $error = 'Add items to cart';
                View::render('sales/create', compact('products', 'error'));
                return;
            }
            $pdo = Database::pdo();
            $pdo->beginTransaction();
            try {
                $subtotal = 0.0;
                foreach ($items as $it) {
                    $pid = (int) $it['product_id'];
                    $qty = (int) $it['quantity'];
                    $product = Database::fetch('SELECT price, quantity FROM products WHERE id = :id', ['id' => $pid]);
                    if (!$product || $product['quantity'] < $qty) {
                        throw new RuntimeException('Insufficient stock for product ID ' . $pid);
                    }
                    $subtotal += $qty * (float) $product['price'];
                }
                $total = $subtotal - $discount + $tax;
                $sale_id = Database::insert('INSERT INTO sales (customer_name, subtotal, discount, tax, total_amount, created_at, user_id) VALUES (:cn,:sub,:dis,:tax,:tot,:c,:u)', [
                    'cn' => $customer_name ?: null,
                    'sub' => $subtotal,
                    'dis' => $discount,
                    'tax' => $tax,
                    'tot' => $total,
                    'c' => date('c'),
                    'u' => current_user()['id'] ?? null,
                ]);
                foreach ($items as $it) {
                    $pid = (int) $it['product_id'];
                    $qty = (int) $it['quantity'];
                    $product = Database::fetch('SELECT price FROM products WHERE id = :id', ['id' => $pid]);
                    $unitPrice = (float) $product['price'];
                    Database::insert('INSERT INTO sale_items (sale_id, product_id, quantity, unit_price) VALUES (:s,:p,:q,:u)', [
                        's' => $sale_id,
                        'p' => $pid,
                        'q' => $qty,
                        'u' => $unitPrice,
                    ]);
                    Database::query('UPDATE products SET quantity = quantity - :q WHERE id = :id', ['q' => $qty, 'id' => $pid]);
                }
                $pdo->commit();
                redirect('sales/view&id=' . $sale_id);
                return;
            } catch (Throwable $e) {
                $pdo->rollBack();
                $error = 'Error: ' . $e->getMessage();
            }
        }
        View::render('sales/create', compact('products', 'error'));
    }

    public function view(): void
    {
        require_login();
        $id = (int) request_param('id');
        $sale = Database::fetch('SELECT s.*, u.name AS user_name FROM sales s LEFT JOIN users u ON u.id = s.user_id WHERE s.id = :id', ['id' => $id]);
        if (!$sale) {
            redirect('sales');
            return;
        }
        $items = Database::fetchAll('SELECT si.*, p.name FROM sale_items si LEFT JOIN products p ON p.id = si.product_id WHERE si.sale_id = :id', ['id' => $id]);
        View::render('sales/view', compact('sale', 'items'));
    }
}