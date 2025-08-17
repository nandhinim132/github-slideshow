<section class="section">
  <div class="level">
    <div class="level-left"><h1 class="title">Purchase #<?= e($purchase['id']) ?></h1></div>
    <div class="level-right"><button class="button" onclick="window.print()">Print</button></div>
  </div>
  <div class="box">
    <p><strong>Supplier:</strong> <?= e($purchase['supplier_name'] ?? '-') ?></p>
    <p><strong>Invoice:</strong> <?= e($purchase['invoice_no']) ?></p>
    <p><strong>Date:</strong> <?= e(date('d M Y, H:i', strtotime($purchase['created_at']))) ?></p>
  </div>
  <table class="table is-fullwidth is-striped">
    <thead><tr><th>#</th><th>Product</th><th>Qty</th><th>Unit Cost</th><th>Total</th></tr></thead>
    <tbody>
      <?php $idx=1; $grand=0; foreach ($items as $it): $line = $it['quantity'] * $it['unit_cost']; $grand += $line; ?>
      <tr>
        <td><?= e((string)$idx++) ?></td>
        <td><?= e($it['name']) ?></td>
        <td><?= e($it['quantity']) ?></td>
        <td class="is-inr"><?= e(format_inr((float)$it['unit_cost'])) ?></td>
        <td class="is-inr"><?= e(format_inr((float)$line)) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr><th colspan="4" style="text-align:right">Total</th><th class="is-inr"><?= e(format_inr($grand)) ?></th></tr>
    </tfoot>
  </table>
  <a class="button" href="<?= e(base_url('purchases')) ?>">Back</a>
</section>