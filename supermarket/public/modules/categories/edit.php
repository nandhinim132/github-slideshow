<?php
require __DIR__ . '/../../../app/config/bootstrap.php';
require __DIR__ . '/../../../app/auth/guard.php';
if (!hasRole([ROLE_ADMIN, ROLE_STOCKER])) { http_response_code(403); exit('Forbidden'); }

$id = (int)($_GET['id'] ?? 0);
$category = dbFetchOne('SELECT id, name, status FROM categories WHERE id = ?', [$id]);
if (!$category) { http_response_code(404); exit('Category not found'); }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) { $errors[] = 'Invalid CSRF token'; }
	$name = trim((string)($_POST['name'] ?? ''));
	$status = ($_POST['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active';

	if ($name === '') { $errors[] = 'Name is required'; }
	if (!$errors) {
		try {
			dbQuery('UPDATE categories SET name = ?, status = ? WHERE id = ?', [$name, $status, $id]);
			redirect(APP_URL_BASE . '/modules/categories/index.php');
		} catch (Throwable $e) {
			$errors[] = 'Could not update category. Name may already exist.';
		}
	}
	$category['name'] = $name;
	$category['status'] = $status;
}

require __DIR__ . '/../../../app/includes/header.php';
require __DIR__ . '/../../../app/includes/navbar.php';
require __DIR__ . '/../../../app/includes/sidebar.php';
$csrf = getCsrfToken();
?>
<main class="content">
	<h1>Edit Category</h1>
	<div class="card">
		<?php if ($errors): ?>
		<ul style="color:#b91c1c;">
			<?php foreach ($errors as $err): ?><li><?php echo sanitize($err); ?></li><?php endforeach; ?>
		</ul>
		<?php endif; ?>
		<form method="post">
			<input type="hidden" name="csrf_token" value="<?php echo sanitize($csrf); ?>">
			<label>Name</label>
			<input type="text" name="name" required value="<?php echo sanitize($category['name']); ?>">
			<label>Status</label>
			<select name="status">
				<option value="active" <?php echo $category['status']==='active'?'selected':''; ?>>Active</option>
				<option value="inactive" <?php echo $category['status']==='inactive'?'selected':''; ?>>Inactive</option>
			</select>
			<button type="submit" class="button" style="margin-top:12px;">Update</button>
			<a href="<?php echo APP_URL_BASE; ?>/modules/categories/index.php" class="button" style="margin-left:8px; background:#e5e7eb; color:#111827;">Cancel</a>
		</form>
	</div>
</main>
<?php require __DIR__ . '/../../../app/includes/footer.php'; ?>