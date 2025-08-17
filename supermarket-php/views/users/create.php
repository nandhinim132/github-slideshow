<section class="section">
  <h1 class="title">Add User</h1>
  <?php if (!empty($error)): ?><div class="notification is-danger"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <div class="field">
      <label class="label">Username</label>
      <input class="input" type="text" name="username" required />
    </div>
    <div class="field">
      <label class="label">Name</label>
      <input class="input" type="text" name="name" required />
    </div>
    <div class="field">
      <label class="label">Role</label>
      <div class="select">
        <select name="role">
          <option value="admin">Admin</option>
          <option value="seller">Seller</option>
          <option value="cashier">Cashier</option>
        </select>
      </div>
    </div>
    <div class="field">
      <label class="label">Password</label>
      <input class="input" type="password" name="password" required />
    </div>
    <div class="field">
      <button class="button is-primary">Save</button>
      <a class="button" href="<?= e(base_url('users')) ?>">Cancel</a>
    </div>
  </form>
</section>