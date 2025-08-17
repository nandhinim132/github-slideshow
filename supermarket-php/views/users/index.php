<section class="section">
  <div class="level">
    <div class="level-left"><h1 class="title">Users</h1></div>
    <div class="level-right"><a class="button is-primary" href="<?= e(base_url('users/create')) ?>">Add User</a></div>
  </div>
  <table class="table is-fullwidth is-striped">
    <thead><tr><th>ID</th><th>Username</th><th>Name</th><th>Role</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?= e($u['id']) ?></td>
          <td><?= e($u['username']) ?></td>
          <td><?= e($u['name']) ?></td>
          <td><?= e($u['role']) ?></td>
          <td>
            <a class="button is-small" href="<?= e(base_url('users/edit&id=' . $u['id'])) ?>">Edit</a>
            <a class="button is-small is-danger" href="<?= e(base_url('users/delete&id=' . $u['id'])) ?>" onclick="return confirm('Delete user?');">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>