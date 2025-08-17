<section class="section">
  <h1 class="title">Edit Product</h1>
  <form method="post">
    <div class="columns">
      <div class="column">
        <div class="field">
          <label class="label">Name</label>
          <input class="input" type="text" name="name" value="<?= e($product['name']) ?>" required />
        </div>
        <div class="field">
          <label class="label">Category</label>
          <div class="select is-fullwidth">
            <select name="category_id">
              <option value="">-- None --</option>
              <?php foreach ($categories as $c): ?>
              <option value="<?= e($c['id']) ?>" <?= (int)$product['category_id']===(int)$c['id']?'selected':'' ?>><?= e($c['name']) ?></option>
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
              <option value="<?= e($s['id']) ?>" <?= (int)$product['supplier_id']===(int)$s['id']?'selected':'' ?>><?= e($s['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="field">
          <label class="label">Price</label>
          <input class="input" type="number" step="0.01" name="price" value="<?= e($product['price']) ?>" required />
        </div>
        <div class="field">
          <label class="label">MRP</label>
          <input class="input" type="number" step="0.01" name="mrp" value="<?= e($product['mrp']) ?>" />
        </div>
        <div class="field">
          <label class="label">Quantity</label>
          <input class="input" type="number" name="quantity" value="<?= e($product['quantity']) ?>" required />
        </div>
        <div class="field">
          <label class="label">Expiry Date</label>
          <input class="input" type="date" name="expiry_date" value="<?= e($product['expiry_date']) ?>" />
        </div>
        <div class="field">
          <label class="label">Barcode</label>
          <input class="input" type="text" name="barcode" value="<?= e($product['barcode']) ?>" />
        </div>
        <div class="field">
          <label class="label">Low-stock Threshold</label>
          <input class="input" type="number" name="stock_threshold" value="<?= e($product['stock_threshold']) ?>" />
        </div>
      </div>
    </div>
    <div class="field">
      <button class="button is-primary">Update</button>
      <a class="button" href="<?= e(base_url('products')) ?>">Cancel</a>
    </div>
  </form>
</section>