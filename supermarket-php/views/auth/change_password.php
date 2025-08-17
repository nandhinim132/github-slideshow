<section class="section">
  <h1 class="title">Change Password</h1>
  <?php if (!empty($error)): ?>
    <div class="notification is-danger"><?= e($error) ?></div>
  <?php endif; ?>
  <?php if (!empty($message)): ?>
    <div class="notification is-success"><?= e($message) ?></div>
  <?php endif; ?>
  <form method="post">
    <input type="hidden" name="<?= e($config['csrf_key']) ?>" value="<?= e($csrf) ?>" />
    <div class="field">
      <label class="label">Current Password</label>
      <div class="control">
        <input class="input" type="password" name="current_password" required />
      </div>
    </div>
    <div class="field">
      <label class="label">New Password</label>
      <div class="control">
        <input class="input" type="password" name="new_password" required />
      </div>
    </div>
    <div class="field">
      <label class="label">Confirm Password</label>
      <div class="control">
        <input class="input" type="password" name="confirm_password" required />
      </div>
    </div>
    <div class="field">
      <button class="button is-primary" type="submit">Update</button>
    </div>
  </form>
</section>