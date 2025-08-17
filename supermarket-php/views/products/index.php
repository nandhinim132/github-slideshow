<section class="section">
  <div class="level">
    <div class="level-left"><h1 class="title">Products</h1></div>
    <div class="level-right"><a class="button is-primary" href="<?= e(base_url('products/create')) ?>">Add Product</a></div>
  </div>
  <table class="table is-fullwidth is-striped is-narrow">
    <thead>
      <tr>
        <th>ID</th><th>Name</th><th>Category</th><th>Supplier</th><th>Price</th><th>MRP</th><th>Qty</th><th>Expiry</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $p): ?>
      <?php $low = $p['quantity'] < $p['stock_threshold']; ?>
      <tr class="<?= $low ? 'has-background-warning-light' : '' ?>">
        <td><?= e($p['id']) ?></td>
        <td><?= e($p['name']) ?></td>
        <td><?= e($p['category_name'] ?? '-') ?></td>
        <td><?= e($p['supplier_name'] ?? '-') ?></td>
        <td class="is-inr"><?= e(format_inr((float)$p['price'])) ?></td>
        <td class="is-inr"><?= e(format_inr((float)$p['mrp'])) ?></td>
        <td><?= e($p['quantity']) ?><?= $low ? ' <span class="low-stock">(Low)</span>' : '' ?></td>
        <td><?= e($p['expiry_date'] ?? '-') ?></td>
        <td>
          <a class="button is-small" href="<?= e(base_url('products/edit&id=' . $p['id'])) ?>">Edit</a>
          <a class="button is-small is-danger" href="<?= e(base_url('products/delete&id=' . $p['id'])) ?>" onclick="return confirm('Delete product?');">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>