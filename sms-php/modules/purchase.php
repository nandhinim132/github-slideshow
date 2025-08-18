<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/util.php';
require_once __DIR__ . '/../includes/db.php';

require_login();
$user = current_user();

$suppliers = db_fetch_all('SELECT id, name FROM suppliers ORDER BY name');
$products = db_fetch_all('SELECT id, name, price FROM products ORDER BY name');

$error = null; $success = null;

if (is_post()) {
    verify_csrf_token(input($_POST, CSRF_TOKEN_KEY));
    $supplier_id = (int)input($_POST, 'supplier_id', 0) ?: null;
    $invoice_no = trim((string)input($_POST, 'invoice_no', ''));
    $items = $_POST['items'] ?? [];
    $pdo = get_pdo();
    $pdo->beginTransaction();
    try {
        $subtotal = 0.0;
        foreach ($items as $it) {
            $qty = (int)($it['qty'] ?? 0);
            $cost = (float)($it['cost'] ?? 0);
            if ($qty > 0 && $cost >= 0) { $subtotal += $qty * $cost; }
        }
        db_exec('INSERT INTO purchases (supplier_id, invoice_no, total, created_by) VALUES (?,?,?,?)', [
            $supplier_id, $invoice_no ?: null, $subtotal, $user['id']
        ]);
        $purchase_id = (int)get_pdo()->lastInsertId();
        foreach ($items as $it) {
            $pid = (int)($it['product_id'] ?? 0);
            $qty = (int)($it['qty'] ?? 0);
            $cost = (float)($it['cost'] ?? 0);
            if ($pid && $qty > 0) {
                db_exec('INSERT INTO purchase_items (purchase_id, product_id, quantity, cost_price, total) VALUES (?,?,?,?,?)', [
                    $purchase_id, $pid, $qty, $cost, $qty * $cost
                ]);
                db_exec('UPDATE products SET quantity = quantity + ? WHERE id = ?', [$qty, $pid]);
            }
        }
        $pdo->commit();
        $success = 'Purchase recorded';
    } catch (Throwable $e) {
        $pdo->rollBack();
        $error = 'Failed to record purchase';
    }
}

$csrf = generate_csrf_token();
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Purchase</title>
    <link rel="stylesheet" href="/style.css">
    <script>
        function addRow(){
            const tbody = document.querySelector('#items tbody');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select name="items[][product_id]">
                        <option value="">-</option>
                        ${Array.from(document.querySelectorAll('#productOptions option')).map(o=>`<option value="${o.value}">${o.text}</option>`).join('')}
                    </select>
                </td>
                <td><input type="number" step="0.01" name="items[][cost]" value="0"></td>
                <td><input type="number" name="items[][qty]" value="1"></td>
                <td><button type="button" class="btn small red" onclick="this.closest('tr').remove()">Remove</button></td>
            `;
            tbody.appendChild(row);
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="actions">
            <a class="btn gray" href="/">Back</a>
        </div>
        <h2>Record Purchase</h2>
        <?php if ($error): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= e($success) ?></div><?php endif; ?>
        <form method="post">
            <input type="hidden" name="<?= e(CSRF_TOKEN_KEY) ?>" value="<?= e($csrf) ?>">
            <div class="field" style="max-width:400px">
                <label>Supplier</label>
                <select name="supplier_id">
                    <option value="">-</option>
                    <?php foreach ($suppliers as $s): ?><option value="<?= (int)$s['id'] ?>"><?= e($s['name']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="field" style="max-width:400px"><label>Invoice No</label><input type="text" name="invoice_no"></div>
            <h3>Items</h3>
            <table id="items">
                <thead><tr><th>Product</th><th>Cost</th><th>Qty</th><th>Action</th></tr></thead>
                <tbody></tbody>
            </table>
            <div class="field"><button type="button" class="btn gray" onclick="addRow()">Add Item</button></div>
            <div class="field"><button class="btn" type="submit">Save Purchase</button></div>
        </form>
        <select id="productOptions" style="display:none">
            <?php foreach ($products as $p): ?><option value="<?= (int)$p['id'] ?>"><?= e($p['name']) ?></option><?php endforeach; ?>
        </select>
    </div>
</body>
</html>

