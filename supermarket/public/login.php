<?php
require __DIR__ . '/../app/config/bootstrap.php';
if (isAuthenticated()) {
	header('Location: ' . APP_URL_BASE . '/index.php');
	exit;
}
$csrf = getCsrfToken();
$flash = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_error']);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login - <?php echo sanitize(APP_NAME); ?></title>
	<link rel="stylesheet" href="<?php echo APP_URL_BASE; ?>/css/style.css">
</head>
<body>
	<div class="login-container">
		<h1>Supermarket Login</h1>
		<?php if ($flash): ?>
		<div style="color:#b91c1c; margin-bottom:8px;"><?php echo sanitize($flash); ?></div>
		<?php endif; ?>
		<form method="post" action="<?php echo APP_URL_BASE; ?>/auth/login.php">
			<input type="hidden" name="csrf_token" value="<?php echo sanitize($csrf); ?>">
			<label>Username</label>
			<input type="text" name="username" required>
			<label>Password</label>
			<input type="password" name="password" required>
			<button type="submit">Login</button>
		</form>
	</div>
</body>
</html>