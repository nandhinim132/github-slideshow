<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/util.php';
require_once __DIR__ . '/../includes/db.php';

require_login();
$user = current_user();

$error = null; $success = null;
if (is_post()) {
    verify_csrf_token(input($_POST, CSRF_TOKEN_KEY));
    $old = (string)input($_POST, 'old_password', '');
    $new = (string)input($_POST, 'new_password', '');
    $confirm = (string)input($_POST, 'confirm_password', '');
    $row = db_fetch('SELECT password_hash FROM users WHERE id = ?', [$user['id']]);
    if (!$row || !verify_password($old, $row['password_hash'])) {
        $error = 'Current password is incorrect';
    } elseif ($new === '' || strlen($new) < 6) {
        $error = 'New password must be at least 6 characters';
    } elseif ($new !== $confirm) {
        $error = 'Passwords do not match';
    } else {
        db_exec('UPDATE users SET password_hash=? WHERE id=?', [hash_password($new), $user['id']]);
        $success = 'Password changed successfully';
    }
}
$csrf = generate_csrf_token();

?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Password</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="container">
        <div class="actions">
            <a class="btn gray" href="/">Back</a>
        </div>
        <h2>Change Password</h2>
        <?php if ($error): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= e($success) ?></div><?php endif; ?>
        <form method="post" style="max-width:400px">
            <input type="hidden" name="<?= e(CSRF_TOKEN_KEY) ?>" value="<?= e($csrf) ?>">
            <div class="field"><label>Current password</label><input type="password" name="old_password" required></div>
            <div class="field"><label>New password</label><input type="password" name="new_password" required></div>
            <div class="field"><label>Confirm new password</label><input type="password" name="confirm_password" required></div>
            <button class="btn" type="submit">Change</button>
        </form>
    </div>
</body>
</html>

