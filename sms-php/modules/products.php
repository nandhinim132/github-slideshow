<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/util.php';
require_once __DIR__ . '/../includes/db.php';

require_login();

$error = null; $success = null;
if (is_post()) {
    verify_csrf_token(input($_POST, CSRF_TOKEN_KEY));
    $action = input($_POST, 'action', '');
    if ($action === 'create') {
        $name = trim((string)input($_POST, 'name', ''));
        $category_id = (int)input($_POST, 'category_id', 0);
        $supplier_id = (int)input($_POST, 'supplier_id', 0);
        $price = (float)input($_POST, 'price', 0);
        $mrp = (float)input($_POST, 'mrp', 0);
        $quantity = (int)input($_POST, 'quantity', 0);
        $reorder = (int)input($_POST, 'reorder_level', 5);
        $expiry = trim((string)input($_POST, 'expiry_date', '')) ?: null;
        $barcode = trim((string)input($_POST, 'barcode', '')) ?: null;
        if ($name !== '') {
            db_exec('INSERT INTO products (name, category_id, supplier_id, price, mrp, quantity, reorder_level, expiry_date, barcode) VALUES (?,?,?,?,?,?,?,?,?)', [
                $name, $category_id ?: null, $supplier_id ?: null, $price, $mrp ?: null, $quantity, $reorder, $expiry, $barcode
            ]);
            $success = 'Product added';
        } else { $error = 'Name required'; }
    } elseif ($action === 'delete') {
        db_exec('DELETE FROM products WHERE id = ?', [(int)input($_POST, 'id', 0)]);
        $success = 'Product deleted';
    } elseif ($action === 'update') {
        $id = (int)input($_POST, 'id', 0);
        $name = trim((string)input($_POST, 'name', ''));
        $category_id = (int)input($_POST, 'category_id', 0);
        $supplier_id = (int)input($_POST, 'supplier_id', 0);
        $price = (float)input($_POST, 'price', 0);
        $mrp = (float)input($_POST, 'mrp', 0);
        $quantity = (int)input($_POST, 'quantity', 0);
        $reorder = (int)input($_POST, 'reorder_level', 5);
        $expiry = trim((string)input($_POST, 'expiry_date', '')) ?: null;
        $barcode = trim((string)input($_POST, 'barcode', '')) ?: null;
        if ($id && $name) {
            db_exec('UPDATE products SET name=?, category_id=?, supplier_id=?, price=?, mrp=?, quantity=?, reorder_level=?, expiry_date=?, barcode=? WHERE id=?', [
                $name, $category_id ?: null, $supplier_id ?: null, $price, $mrp ?: null, $quantity, $reorder, $expiry, $barcode, $id
            ]);
            $success = 'Product updated';
        }
    }
}

$categories = db_fetch_all('SELECT id, name FROM categories ORDER BY name');
$suppliers = db_fetch_all('SELECT id, name FROM suppliers ORDER BY name');
$products = db_fetch_all('SELECT p.*, c.name as category_name, s.name as supplier_name FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN suppliers s ON p.supplier_id = s.id
    ORDER BY p.name');
$csrf = generate_csrf_token();

?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="container">
        <div class="actions">
            <a class="btn gray" href="/">Back</a>
        </div>
        <h2>Products</h2>
        <?php if ($error): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= e($success) ?></div><?php endif; ?>
        <form method="post" style="display:grid; grid-template-columns: repeat(5, 1fr); gap:10px; align-items:end;">
            <input type="hidden" name="<?= e(CSRF_TOKEN_KEY) ?>" value="<?= e($csrf) ?>">
            <input type="hidden" name="action" value="create">
            <div class="field"><label>Name</label><input type="text" name="name" required></div>
            <div class="field"><label>Category</label>
                <select name="category_id"><option value="">-</option>
                    <?php foreach ($categories as $c): ?><option value="<?= (int)$c['id'] ?>"><?= e($c['name']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="field"><label>Supplier</label>
                <select name="supplier_id"><option value="">-</option>
                    <?php foreach ($suppliers as $s): ?><option value="<?= (int)$s['id'] ?>"><?= e($s['name']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="field"><label>Price</label><input type="number" step="0.01" name="price" required></div>
            <div class="field"><label>MRP</label><input type="number" step="0.01" name="mrp"></div>
            <div class="field"><label>Qty</label><input type="number" name="quantity" value="0"></div>
            <div class="field"><label>Reorder</label><input type="number" name="reorder_level" value="5"></div>
            <div class="field"><label>Expiry</label><input type="date" name="expiry_date"></div>
            <div class="field"><label>Barcode</label><input type="text" name="barcode"></div>
            <div><button class="btn" type="submit">Add</button></div>
        </form>

        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Supplier</th><th>Price</th><th>MRP</th><th>Qty</th><th>Reorder</th><th>Expiry</th><th>Barcode</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?= (int)$p['id'] ?></td>
                        <td colspan="10">
                            <form method="post" class="actions" style="flex-wrap:wrap; gap:6px">
                                <input type="hidden" name="<?= e(CSRF_TOKEN_KEY) ?>" value="<?= e($csrf) ?>">
                                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                <input type="text" name="name" value="<?= e($p['name']) ?>" style="width:160px">
                                <select name="category_id">
                                    <option value="">-</option>
                                    <?php foreach ($categories as $c): ?>
                                        <option value="<?= (int)$c['id'] ?>" <?= ((int)$p['category_id'] === (int)$c['id']) ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="supplier_id">
                                    <option value="">-</option>
                                    <?php foreach ($suppliers as $s): ?>
                                        <option value="<?= (int)$s['id'] ?>" <?= ((int)$p['supplier_id'] === (int)$s['id']) ? 'selected' : '' ?>><?= e($s['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="number" step="0.01" name="price" value="<?= e((string)$p['price']) ?>" style="width:90px">
                                <input type="number" step="0.01" name="mrp" value="<?= e((string)($p['mrp'] ?? '')) ?>" style="width:90px">
                                <input type="number" name="quantity" value="<?= (int)$p['quantity'] ?>" style="width:70px">
                                <input type="number" name="reorder_level" value="<?= (int)$p['reorder_level'] ?>" style="width:70px">
                                <input type="date" name="expiry_date" value="<?= e((string)($p['expiry_date'] ?? '')) ?>">
                                <input type="text" name="barcode" value="<?= e((string)($p['barcode'] ?? '')) ?>" style="width:140px">
                                <button class="btn small" name="action" value="update">Save</button>
                                <button class="btn small red" name="action" value="delete" onclick="return confirm('Delete?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

