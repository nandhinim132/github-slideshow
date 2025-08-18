<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/util.php';
require_once __DIR__ . '/../includes/db.php';

require_role([ROLE_ADMIN]);

$error = null; $success = null;
if (is_post()) {
    verify_csrf_token(input($_POST, CSRF_TOKEN_KEY));
    $action = input($_POST, 'action', '');
    if ($action === 'create') {
        $username = trim((string)input($_POST, 'username', ''));
        $name = trim((string)input($_POST, 'name', ''));
        $role = trim((string)input($_POST, 'role', ROLE_SELLER));
        $password = (string)input($_POST, 'password', '');
        if ($username && $password && in_array($role, [ROLE_ADMIN, ROLE_SELLER, ROLE_CASHIER], true)) {
            try {
                db_exec('INSERT INTO users (username, password_hash, name, role) VALUES (?,?,?,?)', [
                    $username, hash_password($password), $name ?: $username, $role
                ]);
                $success = 'User created';
            } catch (Throwable $e) { $error = 'Username exists'; }
        } else { $error = 'Invalid input'; }
    } elseif ($action === 'delete') {
        $id = (int)input($_POST, 'id', 0);
        db_exec('DELETE FROM users WHERE id = ? AND username <> "admin"', [$id]);
        $success = 'User deleted';
    } elseif ($action === 'update') {
        $id = (int)input($_POST, 'id', 0);
        $name = trim((string)input($_POST, 'name', ''));
        $role = trim((string)input($_POST, 'role', ROLE_SELLER));
        if ($id && in_array($role, [ROLE_ADMIN, ROLE_SELLER, ROLE_CASHIER], true)) {
            db_exec('UPDATE users SET name=?, role=? WHERE id=?', [$name, $role, $id]);
            $success = 'User updated';
        }
    } elseif ($action === 'password') {
        $id = (int)input($_POST, 'id', 0);
        $password = (string)input($_POST, 'password', '');
        if ($id && $password) {
            db_exec('UPDATE users SET password_hash=? WHERE id=?', [hash_password($password), $id]);
            $success = 'Password changed';
        }
    }
}

$users = db_fetch_all('SELECT id, username, name, role, created_at FROM users ORDER BY username');
$csrf = generate_csrf_token();
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Users</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="container">
        <div class="actions">
            <a class="btn gray" href="/">Back</a>
        </div>
        <h2>Users</h2>
        <?php if ($error): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= e($success) ?></div><?php endif; ?>
        <form method="post" style="display:grid; grid-template-columns: repeat(5, 1fr); gap:10px; align-items:end; max-width:1000px">
            <input type="hidden" name="<?= e(CSRF_TOKEN_KEY) ?>" value="<?= e($csrf) ?>">
            <input type="hidden" name="action" value="create">
            <div class="field"><label>Username</label><input type="text" name="username" required></div>
            <div class="field"><label>Name</label><input type="text" name="name"></div>
            <div class="field"><label>Role</label>
                <select name="role">
                    <option value="admin">Admin</option>
                    <option value="seller">Seller</option>
                    <option value="cashier">Cashier</option>
                </select>
            </div>
            <div class="field"><label>Password</label><input type="password" name="password" required></div>
            <div><button class="btn" type="submit">Add</button></div>
        </form>

        <table>
            <thead><tr><th>ID</th><th>Username</th><th>Name</th><th>Role</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= (int)$u['id'] ?></td>
                        <td><?= e($u['username']) ?></td>
                        <td colspan="3">
                            <form method="post" class="actions" style="flex-wrap:wrap; gap:6px">
                                <input type="hidden" name="<?= e(CSRF_TOKEN_KEY) ?>" value="<?= e($csrf) ?>">
                                <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                                <input type="text" name="name" value="<?= e($u['name']) ?>" style="width:160px">
                                <select name="role">
                                    <?php foreach (['admin','seller','cashier'] as $role): ?>
                                        <option value="<?= e($role) ?>" <?= $u['role'] === $role ? 'selected' : '' ?>><?= ucfirst($role) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn small" name="action" value="update">Save</button>
                                <input type="password" name="password" placeholder="New password" style="width:160px">
                                <button class="btn small" name="action" value="password">Set Password</button>
                                <?php if ($u['username'] !== 'admin'): ?>
                                <button class="btn small red" name="action" value="delete" onclick="return confirm('Delete?')">Delete</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

