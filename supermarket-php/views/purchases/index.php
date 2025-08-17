<section class="section">
  <div class="level">
    <div class="level-left"><h1 class="title">Purchases</h1></div>
    <div class="level-right"><a class="button is-primary" href="<?= e(base_url('purchases/create')) ?>">Record Purchase</a></div>
  </div>
  <table class="table is-fullwidth is-striped">
    <thead><tr><th>ID</th><th>Supplier</th><th>Invoice</th><th>Total</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($purchases as $p): ?>
      <tr>
        <td><?= e($p['id']) ?></td>
        <td><?= e($p['supplier_name'] ?? '-') ?></td>
        <td><?= e($p['invoice_no']) ?></td>
        <td class="is-inr"><?= e(format_inr((float)$p['total_amount'])) ?></td>
        <td><?= e(date('d M Y, H:i', strtotime($p['created_at']))) ?></td>
        <td><a class="button is-small" href="<?= e(base_url('purchases/view&id=' . $p['id'])) ?>">View</a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>