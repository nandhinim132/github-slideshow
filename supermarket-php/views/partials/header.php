<?php $config = require __DIR__ . '/../../core/config.php'; $user = current_user(); $csrf = generateCsrfToken($config['csrf_key']); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title><?= e($config['app_name']) ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
<style>
.container { margin-top: 20px; }
.is-inr { font-variant-numeric: tabular-nums; }
.low-stock { color: #b71c1c; font-weight: bold; }
</style>
</head>
<body>
<nav class="navbar is-dark" role="navigation">
  <div class="navbar-brand">
    <a class="navbar-item" href="<?= e(base_url('dashboard')) ?>">🏪 <?= e($config['app_name']) ?></a>
  </div>
  <div class="navbar-menu">
    <div class="navbar-start">
      <?php if ($user): ?>
      <a class="navbar-item" href="<?= e(base_url('dashboard')) ?>">Dashboard</a>
      <a class="navbar-item" href="<?= e(base_url('categories')) ?>">Categories</a>
      <a class="navbar-item" href="<?= e(base_url('products')) ?>">Products</a>
      <a class="navbar-item" href="<?= e(base_url('suppliers')) ?>">Suppliers</a>
      <a class="navbar-item" href="<?= e(base_url('inventory')) ?>">Inventory</a>
      <a class="navbar-item" href="<?= e(base_url('purchases')) ?>">Purchases</a>
      <a class="navbar-item" href="<?= e(base_url('sales')) ?>">Sales</a>
      <a class="navbar-item" href="<?= e(base_url('reports')) ?>">Reports</a>
      <?php if ($user['role'] === 'admin'): ?>
      <a class="navbar-item" href="<?= e(base_url('users')) ?>">Users</a>
      <a class="navbar-item" href="<?= e(base_url('employees')) ?>">Employees</a>
      <?php endif; ?>
      <?php endif; ?>
    </div>
    <div class="navbar-end">
      <div class="navbar-item">
        <?php if ($user): ?>
        <div class="buttons">
          <a class="button is-light" href="<?= e(base_url('change-password')) ?>">Change Password</a>
          <a class="button is-danger is-light" href="<?= e(base_url('logout')) ?>">Logout (<?= e($user['name']) ?>)</a>
        </div>
        <?php else: ?>
        <a class="button is-primary" href="<?= e(base_url('login')) ?>">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<div class="container">