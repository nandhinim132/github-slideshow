<?php
?>
<aside class="sidebar">
	<ul>
		<li><a href="<?php echo APP_URL_BASE; ?>/index.php">Dashboard</a></li>
		<?php if (hasRole([ROLE_ADMIN, ROLE_STOCKER])): ?>
		<li><a href="<?php echo APP_URL_BASE; ?>/modules/categories/index.php">Categories</a></li>
		<li><a href="<?php echo APP_URL_BASE; ?>/modules/products/index.php">Products</a></li>
		<li><a href="<?php echo APP_URL_BASE; ?>/modules/suppliers/index.php">Suppliers</a></li>
		<?php endif; ?>
		<?php if (hasRole([ROLE_ADMIN, ROLE_CASHIER, ROLE_SELLER])): ?>
		<li><a href="<?php echo APP_URL_BASE; ?>/modules/sales/pos.php">Sales (POS)</a></li>
		<?php endif; ?>
		<?php if (hasRole([ROLE_ADMIN, ROLE_STOCKER])): ?>
		<li><a href="<?php echo APP_URL_BASE; ?>/modules/purchases/index.php">Purchases</a></li>
		<li><a href="<?php echo APP_URL_BASE; ?>/modules/inventory/index.php">Inventory</a></li>
		<?php endif; ?>
		<?php if (hasRole([ROLE_ADMIN])): ?>
		<li><a href="<?php echo APP_URL_BASE; ?>/modules/customers/index.php">Customers</a></li>
		<li><a href="<?php echo APP_URL_BASE; ?>/modules/reports/index.php">Reports</a></li>
		<?php endif; ?>
	</ul>
</aside>