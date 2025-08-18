<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/util.php';
require_once __DIR__ . '/../includes/db.php';

require_login();

$from = input($_GET, 'from', date('Y-m-01'));
$to = input($_GET, 'to', date('Y-m-d'));

$sales = db_fetch_all('SELECT date(created_at) as d, count(*) as bills, sum(total) as total FROM sales WHERE date(created_at) BETWEEN ? AND ? GROUP BY date(created_at) ORDER BY d', [$from, $to]);
$purchases = db_fetch_all('SELECT date(created_at) as d, count(*) as invoices, sum(total) as total FROM purchases WHERE date(created_at) BETWEEN ? AND ? GROUP BY date(created_at) ORDER BY d', [$from, $to]);

$sales_total = array_sum(array_map(fn($r)=> (float)$r['total'], $sales));
$purchase_total = array_sum(array_map(fn($r)=> (float)$r['total'], $purchases));
$profit = $sales_total - $purchase_total; // naive profit

$employee_sales = db_fetch_all('SELECT u.name as employee, count(s.id) as bills, sum(s.total) as total
    FROM sales s LEFT JOIN users u ON s.created_by = u.id
    WHERE date(s.created_at) BETWEEN ? AND ? GROUP BY u.name ORDER BY total DESC', [$from, $to]);

?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reports</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="container">
        <div class="actions"><a class="btn gray" href="/">Back</a></div>
        <h2>Reports</h2>
        <form method="get" style="display:flex; gap:8px; align-items:flex-end; max-width:600px">
            <div class="field"><label>From</label><input type="date" name="from" value="<?= e($from) ?>"></div>
            <div class="field"><label>To</label><input type="date" name="to" value="<?= e($to) ?>"></div>
            <div><button class="btn" type="submit">Apply</button></div>
        </form>
        <h3>Sales (<?= e($from) ?> to <?= e($to) ?>)</h3>
        <table>
            <thead><tr><th>Date</th><th>Bills</th><th>Total</th></tr></thead>
            <tbody>
                <?php foreach ($sales as $r): ?>
                    <tr><td><?= e($r['d']) ?></td><td><?= (int)$r['bills'] ?></td><td><?= money((float)$r['total']) ?></td></tr>
                <?php endforeach; ?>
                <tr><th>Total</th><th><?= array_sum(array_map(fn($r)=> (int)$r['bills'], $sales)) ?></th><th><?= money($sales_total) ?></th></tr>
            </tbody>
        </table>
        <h3>Purchases</h3>
        <table>
            <thead><tr><th>Date</th><th>Invoices</th><th>Total</th></tr></thead>
            <tbody>
                <?php foreach ($purchases as $r): ?>
                    <tr><td><?= e($r['d']) ?></td><td><?= (int)$r['invoices'] ?></td><td><?= money((float)$r['total']) ?></td></tr>
                <?php endforeach; ?>
                <tr><th>Total</th><th><?= array_sum(array_map(fn($r)=> (int)$r['invoices'], $purchases)) ?></th><th><?= money($purchase_total) ?></th></tr>
            </tbody>
        </table>
        <h3>Profit/Loss</h3>
        <p><strong><?= $profit >= 0 ? 'Profit' : 'Loss' ?>:</strong> <?= money($profit) ?></p>
        <h3>Employee-wise Sales</h3>
        <table>
            <thead><tr><th>Employee</th><th>Bills</th><th>Total</th></tr></thead>
            <tbody>
                <?php foreach ($employee_sales as $r): ?>
                    <tr><td><?= e($r['employee'] ?? 'Unknown') ?></td><td><?= (int)$r['bills'] ?></td><td><?= money((float)$r['total']) ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

