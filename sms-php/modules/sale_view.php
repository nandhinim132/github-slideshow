<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/util.php';
require_once __DIR__ . '/../includes/db.php';

require_login();

$id = (int)($_GET['id'] ?? 0);
$sale = db_fetch('SELECT s.*, u.name as cashier FROM sales s LEFT JOIN users u ON s.created_by = u.id WHERE s.id = ?', [$id]);
if (!$sale) { http_response_code(404); exit('Sale not found'); }
$items = db_fetch_all('SELECT si.*, p.name FROM sale_items si JOIN products p ON si.product_id = p.id WHERE si.sale_id = ?', [$id]);

?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sale #<?= (int)$sale['id'] ?></title>
    <link rel="stylesheet" href="/style.css">
    <style>
        @media print { .no-print { display:none; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="actions no-print">
            <a class="btn gray" href="/modules/reports.php">Back</a>
            <button class="btn" onclick="window.print()">Print</button>
        </div>
        <h2>Invoice #<?= (int)$sale['id'] ?></h2>
        <p>Date: <?= e($sale['created_at']) ?> | Cashier: <?= e($sale['cashier'] ?? 'Unknown') ?> | Customer: <?= e($sale['customer_name'] ?? 'Walk-in') ?></p>
        <table>
            <thead><tr><th>#</th><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
            <tbody>
                <?php $i=1; foreach ($items as $it): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= e($it['name']) ?></td>
                        <td><?= (int)$it['quantity'] ?></td>
                        <td><?= money((float)$it['price']) ?></td>
                        <td><?= money((float)$it['total']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr><th colspan="4" style="text-align:right">Subtotal</th><th><?= money((float)$sale['subtotal']) ?></th></tr>
                <tr><th colspan="4" style="text-align:right">Discount</th><th><?= money((float)$sale['discount']) ?></th></tr>
                <tr><th colspan="4" style="text-align:right">Tax</th><th><?= money((float)$sale['tax']) ?></th></tr>
                <tr><th colspan="4" style="text-align:right">Grand Total</th><th><?= money((float)$sale['total']) ?></th></tr>
            </tfoot>
        </table>
    </div>
</body>
</html>

