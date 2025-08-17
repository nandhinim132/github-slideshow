<section class="section">
  <h1 class="title">Record Purchase</h1>
  <?php if (!empty($error)): ?><div class="notification is-danger"><?= e($error) ?></div><?php endif; ?>
  <form method="post" id="purchaseForm">
    <div class="columns">
      <div class="column is-4">
        <div class="field"><label class="label">Supplier</label>
          <div class="select is-fullwidth"><select name="supplier_id" required>
            <?php foreach ($suppliers as $s): ?>
              <option value="<?= e($s['id']) ?>"><?= e($s['name']) ?></option>
            <?php endforeach; ?>
          </select></div>
        </div>
        <div class="field"><label class="label">Invoice No</label><input class="input" type="text" name="invoice_no" required /></div>
      </div>
    </div>
    <h2 class="subtitle">Items</h2>
    <table class="table is-fullwidth" id="itemsTable">
      <thead><tr><th>Product</th><th>Qty</th><th>Unit Cost (₹)</th><th>Line Total</th><th></th></tr></thead>
      <tbody></tbody>
    </table>
    <div class="field">
      <button class="button is-link" type="button" onclick="addItemRow()">Add Item</button>
    </div>
    <div class="field is-grouped is-grouped-right">
      <div class="control"><strong>Total: <span id="grandTotal">₹ 0.00</span></strong></div>
    </div>
    <div class="field">
      <button class="button is-primary">Save</button>
      <a class="button" href="<?= e(base_url('purchases')) ?>">Cancel</a>
    </div>
  </form>
</section>
<script>
const products = <?= json_encode($products) ?>;
function addItemRow() {
  const tbody = document.querySelector('#itemsTable tbody');
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td>
      <div class="select is-fullwidth">
        <select name="items[][product_id]" required>
          ${products.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
        </select>
      </div>
    </td>
    <td><input class="input" type="number" name="items[][quantity]" value="1" min="1" onchange="recalc()" /></td>
    <td><input class="input" type="number" name="items[][unit_cost]" value="0" step="0.01" onchange="recalc()" /></td>
    <td class="line-total">₹ 0.00</td>
    <td><button class="button is-small is-danger" type="button" onclick="this.closest('tr').remove(); recalc();">Remove</button></td>
  `;
  tbody.appendChild(tr);
  recalc();
}
function recalc() {
  let grand = 0;
  document.querySelectorAll('#itemsTable tbody tr').forEach(tr => {
    const qty = parseFloat(tr.querySelector('input[name="items[][quantity]"]').value) || 0;
    const unit = parseFloat(tr.querySelector('input[name="items[][unit_cost]"]').value) || 0;
    const line = qty * unit;
    tr.querySelector('.line-total').textContent = `₹ ${line.toFixed(2)}`;
    grand += line;
  });
  document.querySelector('#grandTotal').textContent = `₹ ${grand.toFixed(2)}`;
}
addItemRow();
</script>