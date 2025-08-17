<section class="section">
  <h1 class="title">Add Supplier</h1>
  <?php if (!empty($error)): ?><div class="notification is-danger"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <div class="field"><label class="label">Name</label><input class="input" type="text" name="name" required /></div>
    <div class="field"><label class="label">Phone</label><input class="input" type="text" name="phone" /></div>
    <div class="field"><label class="label">Email</label><input class="input" type="email" name="email" /></div>
    <div class="field"><label class="label">Address</label><textarea class="textarea" name="address"></textarea></div>
    <div class="field"><button class="button is-primary">Save</button> <a class="button" href="<?= e(base_url('suppliers')) ?>">Cancel</a></div>
  </form>
</section>