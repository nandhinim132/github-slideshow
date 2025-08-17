<section class="section">
  <h1 class="title">Edit Supplier</h1>
  <form method="post">
    <div class="field"><label class="label">Name</label><input class="input" type="text" name="name" value="<?= e($supplier['name']) ?>" required /></div>
    <div class="field"><label class="label">Phone</label><input class="input" type="text" name="phone" value="<?= e($supplier['phone']) ?>" /></div>
    <div class="field"><label class="label">Email</label><input class="input" type="email" name="email" value="<?= e($supplier['email']) ?>" /></div>
    <div class="field"><label class="label">Address</label><textarea class="textarea" name="address"><?= e($supplier['address']) ?></textarea></div>
    <div class="field"><button class="button is-primary">Update</button> <a class="button" href="<?= e(base_url('suppliers')) ?>">Cancel</a></div>
  </form>
</section>