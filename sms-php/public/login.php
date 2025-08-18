<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/util.php';

start_session_if_needed();

$error = null;
if (is_post()) {
    $username = trim((string)input($_POST, 'username', ''));
    $password = (string)input($_POST, 'password', '');
    verify_csrf_token(input($_POST, CSRF_TOKEN_KEY));
    if (login($username, $password)) {
        redirect('/');
    } else {
        $error = 'Invalid username or password';
    }
}
$csrf = generate_csrf_token();
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f0f2f5; display:flex; align-items:center; justify-content:center; height:100vh; margin:0; }
        .box { background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:24px; width:320px; }
        .box h2 { margin-top:0; }
        .field { margin-bottom:12px; }
        .field label { display:block; font-size:14px; color:#374151; margin-bottom:4px; }
        .field input { width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px; }
        .btn { width:100%; background:#2563eb; color:#fff; border:none; padding:10px; border-radius:6px; cursor:pointer; }
        .btn:hover { background:#1d4ed8; }
        .error { color:#b91c1c; margin-bottom:8px; }
        .muted { color:#6b7280; font-size:12px; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Sign in</h2>
        <?php if ($error): ?>
            <div class="error"><?= e($error) ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <input type="hidden" name="<?= e(CSRF_TOKEN_KEY) ?>" value="<?= e($csrf) ?>">
            <div class="field">
                <label>Username</label>
                <input type="text" name="username" required autofocus>
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button class="btn" type="submit">Login</button>
            <p class="muted">Default admin will be created on first run.</p>
        </form>
    </div>
</body>
</html>

