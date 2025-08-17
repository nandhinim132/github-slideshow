<section class="section">
  <div class="columns is-centered">
    <div class="column is-4">
      <h1 class="title">Login</h1>
      <?php if (!empty($error)): ?>
        <div class="notification is-danger"><?= e($error) ?></div>
      <?php endif; ?>
      <form method="post">
        <input type="hidden" name="<?= e($config['csrf_key']) ?>" value="<?= e($csrf) ?>" />
        <div class="field">
          <label class="label">Username</label>
          <div class="control">
            <input class="input" type="text" name="username" required />
          </div>
        </div>
        <div class="field">
          <label class="label">Password</label>
          <div class="control">
            <input class="input" type="password" name="password" required />
          </div>
        </div>
        <div class="field">
          <div class="control">
            <button class="button is-primary" type="submit">Login</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>