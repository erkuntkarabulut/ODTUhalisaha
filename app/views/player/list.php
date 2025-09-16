<?php include __DIR__ . '/../layout/header.php'; ?>
<?php include __DIR__ . '/../layout/navbar.php'; ?>
<div class="container mt-4">
  <h2>Oyuncular</h2>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Ad</th>
        <th>Email</th>
        <th>Telefon</th>
        <th>Katılım Tarihi</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($players as $player): ?>
      <tr>
        <td><?= $player['player_id'] ?></td>
        <td><?= $player['name'] ?></td>
        <td><?= $player['email'] ?></td>
        <td><?= $player['phone_number'] ?></td>
        <td><?= $player['join_date'] ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>

