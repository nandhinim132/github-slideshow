<section class="section">
  <h1 class="title">New Sale</h1>
  <?php if (!empty($error)): ?><div class="notification is-danger"><?= e($error) ?></div><?php endif; ?>
  <form method="post" id="saleForm">
    <div class="columns">
      <div class="column is-4">
        <div class="field"><label class="label">Customer (optional)</label><input class="input" type="text" name="customer_name" /></div>
        <div class="field"><label class="label">Discount (₹)</label><input class="input" type="number" step="0.01" name="discount" value="0" onchange="recalc()" /></div>
        <div class="field"><label class="label">Tax (₹)</label><input class="input" type="number" step="0.01" name="tax" value="0" onchange="recalc()" /></div>
      </div>
    </div>
    <h2 class="subtitle">Cart</h2>
    <table class="table is-fullwidth" id="itemsTable">
      <thead><tr><th>Product</th><th>Price (₹)</th><th>Qty</th><th>Line Total</th><th></th></tr></thead>
      <tbody></tbody>
    </table>
    <div class="field"><button class="button is-link" type="button" onclick="addItemRow()">Add Item</button></div>
    <div class="field is-grouped is-grouped-right">
      <div class="control"><strong>Subtotal: <span id="subtotal">₹ 0.00</span></strong></div>
    </div>
    <div class="field is-grouped is-grouped-right">
      <div class="control"><strong>Total: <span id="total">₹ 0.00</span></strong></div>
    </div>
    <div class="field">
      <button class="button is-primary">Create Sale</button>
      <a class="button" href="<?= e(base_url('sales')) ?>">Cancel</a>
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
        <select name="items[][product_id]" onchange="updatePrice(this)" required>
          <option value="">-- select --</option>
          ${products.map(p => `<option value="${p.id}" data-price="${p.price}" data-qty="${p.quantity}">${p.name} (Stock: ${p.quantity})</option>`).join('')}
        </select>
      </div>
    </td>
    <td><input class="input price" type="number" step="0.01" name="items[][price]" value="0" readonly /></td>
    <td><input class="input qty" type="number" name="items[][quantity]" value="1" min="1" onchange="recalc()" /></td>
    <td class="line-total">₹ 0.00</td>
    <td><button class="button is-small is-danger" type="button" onclick="this.closest('tr').remove(); recalc();">Remove</button></td>
  `;
  tbody.appendChild(tr);
}
function updatePrice(sel) {
  const opt = sel.options[sel.selectedIndex];
  const price = parseFloat(opt.getAttribute('data-price') || '0');
  const tr = sel.closest('tr');
  tr.querySelector('.price').value = price.toFixed(2);
  recalc();
}
function recalc() {
  let subtotal = 0;
  document.querySelectorAll('#itemsTable tbody tr').forEach(tr => {
    const price = parseFloat(tr.querySelector('.price').value) || 0;
    const qty = parseFloat(tr.querySelector('.qty').value) || 0;
    const line = price * qty;
    tr.querySelector('.line-total').textContent = `₹ ${line.toFixed(2)}`;
    subtotal += line;
  });
  const discount = parseFloat(document.querySelector('input[name="discount"]').value) || 0;
  const tax = parseFloat(document.querySelector('input[name="tax"]').value) || 0;
  const total = subtotal - discount + tax;
  document.querySelector('#subtotal').textContent = `₹ ${subtotal.toFixed(2)}`;
  document.querySelector('#total').textContent = `₹ ${total.toFixed(2)}`;
}
addItemRow();
</script>