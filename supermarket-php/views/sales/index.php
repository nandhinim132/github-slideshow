<section class="section">
  <div class="level">
    <div class="level-left"><h1 class="title">Sales</h1></div>
    <div class="level-right"><a class="button is-primary" href="<?= e(base_url('sales/create')) ?>">New Sale</a></div>
  </div>
  <table class="table is-fullwidth is-striped">
    <thead><tr><th>ID</th><th>Customer</th><th>Subtotal</th><th>Discount</th><th>Tax</th><th>Total</th><th>Date</th></tr></thead>
    <tbody>
      <?php foreach ($sales as $s): ?>
      <tr>
        <td><?= e($s['id']) ?></td>
        <td><?= e($s['customer_name'] ?? '-') ?></td>
        <td class="is-inr"><?= e(format_inr((float)$s['subtotal'])) ?></td>
        <td class="is-inr"><?= e(format_inr((float)$s['discount'])) ?></td>
        <td class="is-inr"><?= e(format_inr((float)$s['tax'])) ?></td>
        <td class="is-inr"><?= e(format_inr((float)$s['total_amount'])) ?></td>
        <td><?= e(date('d M Y, H:i', strtotime($s['created_at']))) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>