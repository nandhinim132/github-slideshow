<section class="section">
  <div class="level">
    <div class="level-left"><h1 class="title">Sale #<?= e($sale['id']) ?></h1></div>
    <div class="level-right"><button class="button" onclick="window.print()">Print</button></div>
  </div>
  <div class="box">
    <p><strong>Customer:</strong> <?= e($sale['customer_name'] ?? '-') ?></p>
    <p><strong>Cashier:</strong> <?= e($sale['user_name'] ?? '-') ?></p>
    <p><strong>Date:</strong> <?= e(date('d M Y, H:i', strtotime($sale['created_at']))) ?></p>
  </div>
  <table class="table is-fullwidth is-striped">
    <thead><tr><th>#</th><th>Product</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr></thead>
    <tbody>
      <?php $idx=1; foreach ($items as $it): $line = $it['quantity'] * $it['unit_price']; ?>
      <tr>
        <td><?= e((string)$idx++) ?></td>
        <td><?= e($it['name']) ?></td>
        <td><?= e($it['quantity']) ?></td>
        <td class="is-inr"><?= e(format_inr((float)$it['unit_price'])) ?></td>
        <td class="is-inr"><?= e(format_inr((float)$line)) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr><th colspan="4" style="text-align:right">Subtotal</th><th class="is-inr"><?= e(format_inr((float)$sale['subtotal'])) ?></th></tr>
      <tr><th colspan="4" style="text-align:right">Discount</th><th class="is-inr">- <?= e(format_inr((float)$sale['discount'])) ?></th></tr>
      <tr><th colspan="4" style="text-align:right">Tax</th><th class="is-inr"><?= e(format_inr((float)$sale['tax'])) ?></th></tr>
      <tr><th colspan="4" style="text-align:right">Total</th><th class="is-inr"><?= e(format_inr((float)$sale['total_amount'])) ?></th></tr>
    </tfoot>
  </table>
  <a class="button" href="<?= e(base_url('sales')) ?>">Back</a>
</section>