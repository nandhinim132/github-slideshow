<section class="section">
  <h1 class="title">Edit Category</h1>
  <form method="post">
    <div class="field">
      <label class="label">Name</label>
      <input class="input" type="text" name="name" value="<?= e($category['name']) ?>" required />
    </div>
    <div class="field">
      <button class="button is-primary">Update</button>
      <a class="button" href="<?= e(base_url('categories')) ?>">Cancel</a>
    </div>
  </form>
</section>