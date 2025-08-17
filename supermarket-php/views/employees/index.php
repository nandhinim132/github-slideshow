<section class="section">
  <div class="level">
    <div class="level-left"><h1 class="title">Employees</h1></div>
    <div class="level-right"><a class="button is-primary" href="<?= e(base_url('employees/create')) ?>">Add Employee</a></div>
  </div>
  <table class="table is-fullwidth is-striped">
    <thead><tr><th>ID</th><th>Name</th><th>Role</th><th>Salary</th><th>Phone</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($employees as $e): ?>
      <tr>
        <td><?= e($e['id']) ?></td>
        <td><?= e($e['name']) ?></td>
        <td><?= e($e['role']) ?></td>
        <td class="is-inr"><?= e(format_inr((float)$e['salary'])) ?></td>
        <td><?= e($e['phone']) ?></td>
        <td>
          <a class="button is-small" href="<?= e(base_url('employees/edit&id=' . $e['id'])) ?>">Edit</a>
          <a class="button is-small is-danger" href="<?= e(base_url('employees/delete&id=' . $e['id'])) ?>" onclick="return confirm('Delete employee?');">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>