<section class="section">
  <h1 class="title">Edit User</h1>
  <form method="post">
    <div class="field">
      <label class="label">Username</label>
      <input class="input" type="text" value="<?= e($user['username']) ?>" disabled />
    </div>
    <div class="field">
      <label class="label">Name</label>
      <input class="input" type="text" name="name" value="<?= e($user['name']) ?>" required />
    </div>
    <div class="field">
      <label class="label">Role</label>
      <div class="select">
        <select name="role">
          <option value="admin" <?= $user['role']==='admin'?'selected':'' ?>>Admin</option>
          <option value="seller" <?= $user['role']==='seller'?'selected':'' ?>>Seller</option>
          <option value="cashier" <?= $user['role']==='cashier'?'selected':'' ?>>Cashier</option>
        </select>
      </div>
    </div>
    <div class="field">
      <button class="button is-primary">Update</button>
      <a class="button" href="<?= e(base_url('users')) ?>">Cancel</a>
    </div>
  </form>
</section>