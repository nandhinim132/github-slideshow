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
        $role = trim((string)input($_POST, 'role', 'seller'));
        $salary = (float)input($_POST, 'salary', 0);
        $phone = trim((string)input($_POST, 'phone', ''));
        if ($name !== '' && in_array($role, ['seller','stocker','cashier'], true)) {
            db_exec('INSERT INTO employees (name, role, salary, phone) VALUES (?, ?, ?, ?)', [$name, $role, $salary, $phone]);
            $success = 'Employee added';
        } else { $error = 'Invalid data'; }
    } elseif ($action === 'delete') {
        db_exec('DELETE FROM employees WHERE id = ?', [(int)input($_POST, 'id', 0)]);
        $success = 'Employee deleted';
    } elseif ($action === 'update') {
        $id = (int)input($_POST, 'id', 0);
        $name = trim((string)input($_POST, 'name', ''));
        $role = trim((string)input($_POST, 'role', 'seller'));
        $salary = (float)input($_POST, 'salary', 0);
        $phone = trim((string)input($_POST, 'phone', ''));
        if ($id && $name && in_array($role, ['seller','stocker','cashier'], true)) {
            db_exec('UPDATE employees SET name=?, role=?, salary=?, phone=? WHERE id=?', [$name, $role, $salary, $phone, $id]);
            $success = 'Employee updated';
        }
    }
}

$employees = db_fetch_all('SELECT * FROM employees ORDER BY created_at DESC');
$csrf = generate_csrf_token();

?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employees</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="container">
        <div class="actions">
            <a class="btn gray" href="/">Back</a>
        </div>
        <h2>Employees</h2>
        <?php if ($error): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= e($success) ?></div><?php endif; ?>
        <form method="post" style="display:grid; grid-template-columns: repeat(5, 1fr); gap:10px; align-items:end; max-width:1000px">
            <input type="hidden" name="<?= e(CSRF_TOKEN_KEY) ?>" value="<?= e($csrf) ?>">
            <input type="hidden" name="action" value="create">
            <div class="field"><label>Name</label><input type="text" name="name" required></div>
            <div class="field"><label>Role</label>
                <select name="role">
                    <option value="seller">Seller</option>
                    <option value="stocker">Stocker</option>
                    <option value="cashier">Cashier</option>
                </select>
            </div>
            <div class="field"><label>Salary</label><input type="number" step="0.01" name="salary" value="0"></div>
            <div class="field"><label>Phone</label><input type="text" name="phone"></div>
            <div><button class="btn" type="submit">Add</button></div>
        </form>

        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Role</th><th>Salary</th><th>Phone</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($employees as $emp): ?>
                    <tr>
                        <td><?= (int)$emp['id'] ?></td>
                        <td colspan="5">
                            <form method="post" class="actions" style="flex-wrap:wrap; gap:6px">
                                <input type="hidden" name="<?= e(CSRF_TOKEN_KEY) ?>" value="<?= e($csrf) ?>">
                                <input type="hidden" name="id" value="<?= (int)$emp['id'] ?>">
                                <input type="text" name="name" value="<?= e($emp['name']) ?>" style="width:180px">
                                <select name="role">
                                    <?php foreach (['seller','stocker','cashier'] as $role): ?>
                                        <option value="<?= e($role) ?>" <?= $emp['role'] === $role ? 'selected' : '' ?>><?= ucfirst($role) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="number" step="0.01" name="salary" value="<?= e((string)$emp['salary']) ?>" style="width:120px">
                                <input type="text" name="phone" value="<?= e((string)($emp['phone'] ?? '')) ?>" style="width:140px">
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

