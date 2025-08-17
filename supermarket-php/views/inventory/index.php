<section class="section">
  <h1 class="title">Inventory</h1>
  <table class="table is-fullwidth is-striped is-narrow">
    <thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Qty</th><th>Threshold</th></tr></thead>
    <tbody>
      <?php foreach ($products as $p): ?>
      <?php $low = $p['quantity'] < $p['stock_threshold']; ?>
      <tr class="<?= $low ? 'has-background-warning-light' : '' ?>">
        <td><?= e($p['id']) ?></td>
        <td><?= e($p['name']) ?></td>
        <td><?= e($p['category_name'] ?? '-') ?></td>
        <td><?= e($p['quantity']) ?><?= $low ? ' <span class="low-stock">(Low)</span>' : '' ?></td>
        <td><?= e($p['stock_threshold']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>