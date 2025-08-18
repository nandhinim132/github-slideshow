<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/util.php';
require_once __DIR__ . '/../includes/db.php';

require_login();
$user = current_user();

$products = db_fetch_all('SELECT id, name, price, quantity FROM products ORDER BY name');

$error = null; $success = null; $saleId = null;

if (is_post()) {
    verify_csrf_token(input($_POST, CSRF_TOKEN_KEY));
    $customer = trim((string)input($_POST, 'customer_name', '')) ?: null;
    $discount = (float)input($_POST, 'discount', 0);
    $tax = (float)input($_POST, 'tax', 0);
    $items = $_POST['items'] ?? [];
    $pdo = get_pdo();
    $pdo->beginTransaction();
    try {
        $subtotal = 0.0;
        foreach ($items as $it) {
            $pid = (int)($it['product_id'] ?? 0);
            $qty = (int)($it['qty'] ?? 0);
            $product = $pid ? db_fetch('SELECT price, quantity FROM products WHERE id = ?', [$pid]) : null;
            if ($product && $qty > 0 && $product['quantity'] >= $qty) {
                $subtotal += $qty * (float)$product['price'];
            } else {
                throw new Exception('Insufficient stock for product ID ' . $pid);
            }
        }
        $total = max(0, $subtotal - $discount + $tax);
        db_exec('INSERT INTO sales (customer_name, subtotal, discount, tax, total, created_by) VALUES (?,?,?,?,?,?)', [
            $customer, $subtotal, $discount, $tax, $total, $user['id']
        ]);
        $saleId = (int)get_pdo()->lastInsertId();
        foreach ($items as $it) {
            $pid = (int)($it['product_id'] ?? 0);
            $qty = (int)($it['qty'] ?? 0);
            $product = db_fetch('SELECT price FROM products WHERE id = ?', [$pid]);
            if ($pid && $qty > 0 && $product) {
                $price = (float)$product['price'];
                db_exec('INSERT INTO sale_items (sale_id, product_id, quantity, price, total) VALUES (?,?,?,?,?)', [
                    $saleId, $pid, $qty, $price, $price * $qty
                ]);
                db_exec('UPDATE products SET quantity = quantity - ? WHERE id = ?', [$qty, $pid]);
            }
        }
        $pdo->commit();
        $success = 'Sale completed. ID #' . $saleId;
    } catch (Throwable $e) {
        $pdo->rollBack();
        $error = 'Failed to complete sale: ' . $e->getMessage();
    }
}

$csrf = generate_csrf_token();
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales</title>
    <link rel="stylesheet" href="/style.css">
    <script>
        function addRow(){
            const tbody = document.querySelector('#items tbody');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select name="items[][product_id]" onchange="updatePrice(this)">
                        <option value="">-</option>
                        ${Array.from(document.querySelectorAll('#productOptions option')).map(o=>`<option data-price="${o.dataset.price}" data-stock="${o.dataset.stock}" value="${o.value}">${o.text}</option>`).join('')}
                    </select>
                </td>
                <td><input type="number" step="0.01" name="price" disabled></td>
                <td><input type="number" name="items[][qty]" value="1"></td>
                <td><button type="button" class="btn small red" onclick="this.closest('tr').remove()">Remove</button></td>
            `;
            tbody.appendChild(row);
        }
        function updatePrice(sel){
            const opt = sel.selectedOptions[0];
            if (!opt) return;
            const price = opt.dataset.price || '0';
            sel.closest('tr').querySelector('input[name="price"]').value = price;
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="actions">
            <a class="btn gray" href="/">Back</a>
        </div>
        <h2>Sales</h2>
        <?php if ($error): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= e($success) ?></div><?php endif; ?>
        <form method="post">
            <input type="hidden" name="<?= e(CSRF_TOKEN_KEY) ?>" value="<?= e($csrf) ?>">
            <div class="field" style="max-width:400px"><label>Customer (optional)</label><input type="text" name="customer_name"></div>
            <h3>Items</h3>
            <table id="items">
                <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Action</th></tr></thead>
                <tbody></tbody>
            </table>
            <div class="field"><button type="button" class="btn gray" onclick="addRow()">Add Item</button></div>
            <div style="display:flex; gap:12px; max-width:600px">
                <div class="field" style="flex:1"><label>Discount</label><input type="number" step="0.01" name="discount" value="0"></div>
                <div class="field" style="flex:1"><label>Tax</label><input type="number" step="0.01" name="tax" value="0"></div>
            </div>
            <div class="field"><button class="btn" type="submit">Complete Sale</button></div>
        </form>
        <select id="productOptions" style="display:none">
            <?php foreach ($products as $p): ?><option data-price="<?= e((string)$p['price']) ?>" data-stock="<?= (int)$p['quantity'] ?>" value="<?= (int)$p['id'] ?>"><?= e($p['name']) ?> (<?= money((float)$p['price']) ?>)</option><?php endforeach; ?>
        </select>
    </div>
</body>
</html>

