<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/util.php';

require_login();

$user = current_user();

?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f6f8fa; }
        header { background: #111827; color: #fff; padding: 12px 20px; display:flex; align-items:center; justify-content:space-between; }
        nav a { color:#e5e7eb; margin-right: 12px; text-decoration:none; }
        nav a:hover { color:#fff; }
        .container { padding: 20px; }
        .card-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap:16px; }
        .card { background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:16px; }
        .muted { color:#6b7280; }
        .btn { display:inline-block; background:#2563eb; color:#fff; padding:8px 12px; border-radius:6px; text-decoration:none; }
        .btn:hover { background:#1d4ed8; }
    </style>
    </head>
<body>
    <header>
        <div><strong>Supermarket</strong> — Welcome, <?= e($user['name']) ?> (<?= e($user['role']) ?>)</div>
        <nav>
            <a href="/modules/sales.php">Sales</a>
            <a href="/modules/purchase.php">Purchase</a>
            <a href="/modules/inventory.php">Inventory</a>
            <a href="/modules/products.php">Products</a>
            <a href="/modules/categories.php">Categories</a>
            <a href="/modules/suppliers.php">Suppliers</a>
            <a href="/modules/employees.php">Employees</a>
            <a href="/modules/users.php">Users</a>
            <a href="/modules/reports.php">Reports</a>
            <a href="/change_password.php">Change Password</a>
            <a href="/logout.php">Logout</a>
        </nav>
    </header>
    <div class="container">
        <h2>Dashboard</h2>
        <p class="muted">Choose a module from the navigation above.</p>
        <div class="card-grid">
            <div class="card">
                <h3>Quick Sale</h3>
                <p>Create a new bill and deduct stock.</p>
                <a class="btn" href="/modules/sales.php">Start</a>
            </div>
            <div class="card">
                <h3>New Purchase</h3>
                <p>Record goods received and update stock.</p>
                <a class="btn" href="/modules/purchase.php">Record</a>
            </div>
            <div class="card">
                <h3>Low Stock</h3>
                <p>Check items below threshold.</p>
                <a class="btn" href="/modules/inventory.php#low">View</a>
            </div>
        </div>
    </div>
</body>
</html>

