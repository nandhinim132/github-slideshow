<section class="section">
  <h1 class="title">Add Employee</h1>
  <form method="post">
    <div class="field"><label class="label">Name</label><input class="input" type="text" name="name" required /></div>
    <div class="field"><label class="label">Role</label>
      <div class="select"><select name="role">
        <option value="seller">Seller</option>
        <option value="stocker">Stocker</option>
        <option value="cashier">Cashier</option>
      </select></div>
    </div>
    <div class="field"><label class="label">Salary (₹)</label><input class="input" type="number" step="0.01" name="salary" value="0" /></div>
    <div class="field"><label class="label">Phone</label><input class="input" type="text" name="phone" /></div>
    <div class="field"><button class="button is-primary">Save</button> <a class="button" href="<?= e(base_url('employees')) ?>">Cancel</a></div>
  </form>
</section>