<section class="section">
  <h1 class="title">Edit Employee</h1>
  <form method="post">
    <div class="field"><label class="label">Name</label><input class="input" type="text" name="name" value="<?= e($employee['name']) ?>" required /></div>
    <div class="field"><label class="label">Role</label>
      <div class="select"><select name="role">
        <option value="seller" <?= $employee['role']==='seller'?'selected':'' ?>>Seller</option>
        <option value="stocker" <?= $employee['role']==='stocker'?'selected':'' ?>>Stocker</option>
        <option value="cashier" <?= $employee['role']==='cashier'?'selected':'' ?>>Cashier</option>
      </select></div>
    </div>
    <div class="field"><label class="label">Salary (₹)</label><input class="input" type="number" step="0.01" name="salary" value="<?= e($employee['salary']) ?>" /></div>
    <div class="field"><label class="label">Phone</label><input class="input" type="text" name="phone" value="<?= e($employee['phone']) ?>" /></div>
    <div class="field"><button class="button is-primary">Update</button> <a class="button" href="<?= e(base_url('employees')) ?>">Cancel</a></div>
  </form>
</section>