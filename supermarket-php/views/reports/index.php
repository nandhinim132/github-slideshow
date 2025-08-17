<?php
// ... existing code ...
?>
<section class="section">
  <h1 class="title">Reports</h1>
  <form method="get" class="box">
    <input type="hidden" name="r" value="reports" />
    <div class="columns">
      <div class="column is-3"><label class="label">From</label><input class="input" type="date" name="from" value="<?= e($from) ?>" /></div>
      <div class="column is-3"><label class="label">To</label><input class="input" type="date" name="to" value="<?= e($to) ?>" /></div>
      <div class="column is-3"><label class="label">&nbsp;</label><button class="button is-primary is-fullwidth">Filter</button></div>
    </div>
  </form>
  <div class="columns">
    <div class="column">
      <div class="box"><p class="heading">Sales</p><p class="title is-inr"><?= e(format_inr($salesTotal)) ?></p></div>
    </div>
    <div class="column">
      <div class="box"><p class="heading">Purchases</p><p class="title is-inr"><?= e(format_inr($purchaseTotal)) ?></p></div>
    </div>
    <div class="column">
      <div class="box"><p class="heading">Profit/Loss</p><p class="title is-inr"><?= e(format_inr($profit)) ?></p></div>
    </div>
  </div>

  <?php if (!empty($employeeSales)): ?>
  <h2 class="title is-5">Employee-wise Sales</h2>
  <table class="table is-fullwidth is-striped">
    <thead><tr><th>Employee</th><th>Role</th><th>Sales Count</th><th>Total Sales</th></tr></thead>
    <tbody>
      <?php foreach ($employeeSales as $emp): ?>
      <tr>
        <td><?= e($emp['name'] ?? 'Unknown') ?></td>
        <td><?= e($emp['role'] ?? '-') ?></td>
        <td><?= e($emp['sales_count']) ?></td>
        <td class="is-inr"><?= e(format_inr((float)$emp['total_sales'])) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</section>