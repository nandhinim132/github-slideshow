<?php
require __DIR__ . '/../../../app/config/bootstrap.php';
require __DIR__ . '/../../../app/auth/guard.php';
require __DIR__ . '/../../../app/includes/header.php';
require __DIR__ . '/../../../app/includes/navbar.php';
require __DIR__ . '/../../../app/includes/sidebar.php';

if (!hasRole([ROLE_ADMIN, ROLE_STOCKER])) {
	echo '<main class="content"><h1>Forbidden</h1><p>You do not have access to Categories.</p></main>';
	require __DIR__ . '/../../../app/includes/footer.php';
	exit;
}

$categories = dbFetchAll('SELECT id, name, status, created_at FROM categories ORDER BY name ASC');
$csrf = getCsrfToken();
?>
<main class="content">
	<h1>Categories</h1>
	<p><a class="button" href="<?php echo APP_URL_BASE; ?>/modules/categories/create.php">Add Category</a></p>
	<div class="card">
		<table style="width:100%; border-collapse: collapse;">
			<thead>
				<tr>
					<th style="text-align:left; padding:8px; border-bottom:1px solid #e5e7eb;">Name</th>
					<th style="text-align:left; padding:8px; border-bottom:1px solid #e5e7eb;">Status</th>
					<th style="text-align:left; padding:8px; border-bottom:1px solid #e5e7eb;">Created</th>
					<th style="padding:8px; border-bottom:1px solid #e5e7eb;">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($categories as $cat): ?>
				<tr>
					<td style="padding:8px; border-bottom:1px solid #f1f5f9;"><?php echo sanitize($cat['name']); ?></td>
					<td style="padding:8px; border-bottom:1px solid #f1f5f9;"><?php echo sanitize($cat['status']); ?></td>
					<td style="padding:8px; border-bottom:1px solid #f1f5f9;"><?php echo sanitize($cat['created_at']); ?></td>
					<td style="padding:8px; border-bottom:1px solid #f1f5f9; white-space:nowrap;">
						<a href="<?php echo APP_URL_BASE; ?>/modules/categories/edit.php?id=<?php echo (int)$cat['id']; ?>">Edit</a>
						<form method="post" action="<?php echo APP_URL_BASE; ?>/modules/categories/delete.php" style="display:inline; margin-left:8px;" onsubmit="return confirm('Delete this category?');">
							<input type="hidden" name="csrf_token" value="<?php echo sanitize($csrf); ?>">
							<input type="hidden" name="id" value="<?php echo (int)$cat['id']; ?>">
							<button type="submit" style="background:none; border:none; color:#b91c1c; cursor:pointer;">Delete</button>
						</form>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php if (count($categories) === 0): ?>
				<tr><td colspan="4" style="padding:12px; text-align:center; color:#6b7280;">No categories yet.</td></tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</main>
<?php require __DIR__ . '/../../../app/includes/footer.php'; ?>