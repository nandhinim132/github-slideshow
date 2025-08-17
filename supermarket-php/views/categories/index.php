<section class="section">
  <div class="level">
    <div class="level-left"><h1 class="title">Categories</h1></div>
    <div class="level-right"><a class="button is-primary" href="<?= e(base_url('categories/create')) ?>">Add Category</a></div>
  </div>
  <table class="table is-fullwidth is-striped">
    <thead><tr><th>ID</th><th>Name</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($categories as $c): ?>
        <tr>
          <td><?= e($c['id']) ?></td>
          <td><?= e($c['name']) ?></td>
          <td>
            <a class="button is-small" href="<?= e(base_url('categories/edit&id=' . $c['id'])) ?>">Edit</a>
            <a class="button is-small is-danger" href="<?= e(base_url('categories/delete&id=' . $c['id'])) ?>" onclick="return confirm('Delete category?');">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>