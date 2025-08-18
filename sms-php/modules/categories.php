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
        if ($name !== '') {
            try {
                db_exec('INSERT INTO categories (name) VALUES (?)', [$name]);
                $success = 'Category added';
            } catch (Throwable $e) { $error = 'Duplicate or invalid'; }
        } else { $error = 'Name required'; }
    } elseif ($action === 'delete') {
        $id = (int)input($_POST, 'id', 0);
        db_exec('DELETE FROM categories WHERE id = ?', [$id]);
        $success = 'Category deleted';
    } elseif ($action === 'update') {
        $id = (int)input($_POST, 'id', 0);
        $name = trim((string)input($_POST, 'name', ''));
        if ($id && $name) {
            db_exec('UPDATE categories SET name = ? WHERE id = ?', [$name, $id]);
            $success = 'Category updated';
        }
    }
}

$categories = db_fetch_all('SELECT * FROM categories ORDER BY name');
$csrf = generate_csrf_token();

?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Categories</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="container">
        <div class="actions">
            <a class="btn gray" href="/">Back</a>
        </div>
        <h2>Categories</h2>
        <?php if ($error): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= e($success) ?></div><?php endif; ?>
        <form method="post" class="field" style="max-width:400px">
            <input type="hidden" name="<?= e(CSRF_TOKEN_KEY) ?>" value="<?= e($csrf) ?>">
            <input type="hidden" name="action" value="create">
            <label>Name</label>
            <input type="text" name="name" required>
            <div class="field"><button class="btn" type="submit">Add</button></div>
        </form>

        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($categories as $c): ?>
                    <tr>
                        <td><?= (int)$c['id'] ?></td>
                        <td>
                            <form method="post" class="actions" style="gap:6px">
                                <input type="hidden" name="<?= e(CSRF_TOKEN_KEY) ?>" value="<?= e($csrf) ?>">
                                <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                                <input type="text" name="name" value="<?= e($c['name']) ?>">
                                <button class="btn small" name="action" value="update">Save</button>
                                <button class="btn small red" name="action" value="delete" onclick="return confirm('Delete?')">Delete</button>
                            </form>
                        </td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

