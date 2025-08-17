<section class="section">
  <div class="level">
    <div class="level-left"><h1 class="title">Suppliers</h1></div>
    <div class="level-right"><a class="button is-primary" href="<?= e(base_url('suppliers/create')) ?>">Add Supplier</a></div>
  </div>
  <table class="table is-fullwidth is-striped">
    <thead><tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($suppliers as $s): ?>
      <tr>
        <td><?= e($s['id']) ?></td>
        <td><?= e($s['name']) ?></td>
        <td><?= e($s['phone']) ?></td>
        <td><?= e($s['email']) ?></td>
        <td>
          <a class="button is-small" href="<?= e(base_url('suppliers/edit&id=' . $s['id'])) ?>">Edit</a>
          <a class="button is-small is-danger" href="<?= e(base_url('suppliers/delete&id=' . $s['id'])) ?>" onclick="return confirm('Delete supplier?');">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>