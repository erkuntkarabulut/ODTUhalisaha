<?php include __DIR__ . '/../layout/header.php'; ?>
<?php include __DIR__ . '/../layout/navbar.php'; ?>
<div class="container mt-4">
  <h2>Maçlar</h2>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Tarih</th>
        <th>Saati</th>
        <th>Lokasyon</th>
        <th>Ücret</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($matches as $match): ?>
      <tr>
        <td><?= $match['match_id'] ?></td>
        <td><?= $match['match_date'] ?></td>
        <td><?= $match['match_time'] ?></td>
        <td><?= $match['location'] ?></td>
        <td><?= $match['match_fee'] ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>

