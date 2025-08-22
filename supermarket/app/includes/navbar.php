<?php
$user = currentUser();
?>
<header class="topbar">
	<div class="brand">₹ <?php echo sanitize(APP_NAME); ?></div>
	<nav>
		<?php if ($user): ?>
		<span class="user">Hello, <?php echo sanitize($user['full_name'] ?? $user['username']); ?></span>
		<a href="<?php echo APP_URL_BASE; ?>/index.php">Dashboard</a>
		<a href="<?php echo APP_URL_BASE; ?>/auth/logout.php">Logout</a>
		<?php endif; ?>
	</nav>
</header>