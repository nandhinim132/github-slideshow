<section class="section">
  <h1 class="title">Add Product</h1>
  <?php if (!empty($error)): ?><div class="notification is-danger"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <div class="columns">
      <div class="column">
        <div class="field">
          <label class="label">Name</label>
          <input class="input" type="text" name="name" required />
        </div>
        <div class="field">
          <label class="label">Category</label>
          <div class="select is-fullwidth">
            <select name="category_id">
              <option value="">-- None --</option>
              <?php foreach ($categories as $c): ?>
              <option value="<?= e($c['id']) ?>"><?= e($c['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="field">
          <label class="label">Supplier</label>
          <div class="select is-fullwidth">
            <select name="supplier_id">
              <option value="">-- None --</option>
              <?php foreach ($suppliers as $s): ?>
              <option value="<?= e($s['id']) ?>"><?= e($s['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="field">
          <label class="label">Price</label>
          <input class="input" type="number" step="0.01" name="price" required />
        </div>
        <div class="field">
          <label class="label">MRP</label>
          <input class="input" type="number" step="0.01" name="mrp" />
        </div>
        <div class="field">
          <label class="label">Quantity</label>
          <input class="input" type="number" name="quantity" required />
        </div>
        <div class="field">
          <label class="label">Expiry Date</label>
          <input class="input" type="date" name="expiry_date" />
        </div>
        <div class="field">
          <label class="label">Barcode</label>
          <input class="input" type="text" name="barcode" />
        </div>
        <div class="field">
          <label class="label">Low-stock Threshold</label>
          <input class="input" type="number" name="stock_threshold" value="5" />
        </div>
      </div>
    </div>
    <div class="field">
      <button class="button is-primary">Save</button>
      <a class="button" href="<?= e(base_url('products')) ?>">Cancel</a>
    </div>
  </form>
</section>