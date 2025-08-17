<?php

class PurchaseController
{
    public function index(): void
    {
        require_login();
        $purchases = Database::fetchAll('SELECT pu.*, s.name AS supplier_name FROM purchases pu LEFT JOIN suppliers s ON s.id = pu.supplier_id ORDER BY pu.id DESC');
        View::render('purchases/index', compact('purchases'));
    }

    public function create(): void
    {
        Auth::requireRole(['admin']);
        $suppliers = Database::fetchAll('SELECT * FROM suppliers ORDER BY name');
        $products = Database::fetchAll('SELECT id, name FROM products ORDER BY name');
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $supplier_id = (int) request_param('supplier_id');
            $invoice_no = trim((string) request_param('invoice_no'));
            $items = $_POST['items'] ?? [];
            if (!is_array($items) || count($items) === 0) {
                $error = 'Add at least one item';
                View::render('purchases/create', compact('suppliers', 'products', 'error'));
                return;
            }
            $pdo = Database::pdo();
            $pdo->beginTransaction();
            try {
                $total = 0.0;
                foreach ($items as $it) {
                    $qty = (int) ($it['quantity'] ?? 0);
                    $unit = (float) ($it['unit_cost'] ?? 0);
                    $total += $qty * $unit;
                }
                $purchase_id = Database::insert('INSERT INTO purchases (supplier_id, invoice_no, total_amount, created_at) VALUES (:s,:i,:t,:c)', [
                    's' => $supplier_id,
                    'i' => $invoice_no,
                    't' => $total,
                    'c' => date('c'),
                ]);
                foreach ($items as $it) {
                    $pid = (int) $it['product_id'];
                    $qty = (int) $it['quantity'];
                    $unit = (float) $it['unit_cost'];
                    Database::insert('INSERT INTO purchase_items (purchase_id, product_id, quantity, unit_cost) VALUES (:p,:pr,:q,:u)', [
                        'p' => $purchase_id,
                        'pr' => $pid,
                        'q' => $qty,
                        'u' => $unit,
                    ]);
                    Database::query('UPDATE products SET quantity = quantity + :q WHERE id = :id', ['q' => $qty, 'id' => $pid]);
                }
                $pdo->commit();
                redirect('purchases/view&id=' . $purchase_id);
                return;
            } catch (Throwable $e) {
                $pdo->rollBack();
                $error = 'Error: ' . $e->getMessage();
            }
        }
        View::render('purchases/create', compact('suppliers', 'products', 'error'));
    }

    public function view(): void
    {
        require_login();
        $id = (int) request_param('id');
        $purchase = Database::fetch('SELECT pu.*, s.name AS supplier_name, s.phone, s.email, s.address FROM purchases pu LEFT JOIN suppliers s ON s.id = pu.supplier_id WHERE pu.id = :id', ['id' => $id]);
        if (!$purchase) {
            redirect('purchases');
            return;
        }
        $items = Database::fetchAll('SELECT pi.*, p.name FROM purchase_items pi LEFT JOIN products p ON p.id = pi.product_id WHERE pi.purchase_id = :id', ['id' => $id]);
        View::render('purchases/view', compact('purchase', 'items'));
    }
}