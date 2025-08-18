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
        $phone = trim((string)input($_POST, 'phone', ''));
        $email = trim((string)input($_POST, 'email', ''));
        $address = trim((string)input($_POST, 'address', ''));
        if ($name !== '') {
            try {
                db_exec('INSERT INTO suppliers (name, phone, email, address) VALUES (?, ?, ?, ?)', [$name, $phone, $email, $address]);
                $success = 'Supplier added';
            } catch (Throwable $e) { $error = 'Duplicate or invalid'; }
        } else { $error = 'Name required'; }
    } elseif ($action === 'delete') {
        db_exec('DELETE FROM suppliers WHERE id = ?', [(int)input($_POST, 'id', 0)]);
        $success = 'Supplier deleted';
    } elseif ($action === 'update') {
        $id = (int)input($_POST, 'id', 0);
        $name = trim((string)input($_POST, 'name', ''));
        $phone = trim((string)input($_POST, 'phone', ''));
        $email = trim((string)input($_POST, 'email', ''));
        $address = trim((string)input($_POST, 'address', ''));
        if ($id && $name) {
            db_exec('UPDATE suppliers SET name = ?, phone = ?, email = ?, address = ? WHERE id = ?', [$name, $phone, $email, $address, $id]);
            $success = 'Supplier updated';
        }
    }
}

$suppliers = db_fetch_all('SELECT * FROM suppliers ORDER BY name');
$csrf = generate_csrf_token();
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Suppliers</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="container">
        <div class="actions">
            <a class="btn gray" href="/">Back</a>
        </div>
        <h2>Suppliers</h2>
        <?php if ($error): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= e($success) ?></div><?php endif; ?>
        <form method="post" style="max-width:600px">
            <input type="hidden" name="<?= e(CSRF_TOKEN_KEY) ?>" value="<?= e($csrf) ?>">
            <input type="hidden" name="action" value="create">
            <div class="field"><label>Name</label><input type="text" name="name" required></div>
            <div class="field"><label>Phone</label><input type="text" name="phone"></div>
            <div class="field"><label>Email</label><input type="email" name="email"></div>
            <div class="field"><label>Address</label><input type="text" name="address"></div>
            <button class="btn" type="submit">Add</button>
        </form>

        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Address</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($suppliers as $s): ?>
                <tr>
                    <td><?= (int)$s['id'] ?></td>
                    <td colspan="5">
                        <form method="post" class="actions" style="gap:6px">
                            <input type="hidden" name="<?= e(CSRF_TOKEN_KEY) ?>" value="<?= e($csrf) ?>">
                            <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                            <input type="text" name="name" value="<?= e($s['name']) ?>" style="width:160px">
                            <input type="text" name="phone" value="<?= e($s['phone'] ?? '') ?>" style="width:120px">
                            <input type="email" name="email" value="<?= e($s['email'] ?? '') ?>" style="width:200px">
                            <input type="text" name="address" value="<?= e($s['address'] ?? '') ?>" style="flex:1">
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

