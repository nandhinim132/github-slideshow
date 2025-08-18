<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/util.php';
require_once __DIR__ . '/../includes/db.php';

require_login();

$low = db_fetch_all('SELECT * FROM products WHERE quantity < reorder_level ORDER BY quantity ASC');
$all = db_fetch_all('SELECT * FROM products ORDER BY name');
$csrf = generate_csrf_token();

?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="container">
        <div class="actions">
            <a class="btn gray" href="/">Back</a>
            <a class="btn" href="#low">Low Stock</a>
        </div>
        <h2>Inventory</h2>
        <h3 id="low">Low Stock</h3>
        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Qty</th><th>Reorder</th></tr></thead>
            <tbody>
                <?php foreach ($low as $p): ?>
                <tr><td><?= (int)$p['id'] ?></td><td><?= e($p['name']) ?></td><td><?= (int)$p['quantity'] ?></td><td><?= (int)$p['reorder_level'] ?></td></tr>
                <?php endforeach; ?>
                <?php if (!$low): ?><tr><td colspan="4" class="muted">No low-stock items</td></tr><?php endif; ?>
            </tbody>
        </table>
        <h3>All Products</h3>
        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Qty</th><th>Reorder</th><th>Expiry</th></tr></thead>
            <tbody>
                <?php foreach ($all as $p): ?>
                <tr><td><?= (int)$p['id'] ?></td><td><?= e($p['name']) ?></td><td><?= (int)$p['quantity'] ?></td><td><?= (int)$p['reorder_level'] ?></td><td><?= e((string)($p['expiry_date'] ?? '')) ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

