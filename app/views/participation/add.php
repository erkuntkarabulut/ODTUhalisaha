<?php include __DIR__ . '/../layout/header.php'; ?>
<?php include __DIR__ . '/../layout/navbar.php'; ?>

<div class="container mt-4">
  <h2>Katılım Kaydı Ekle</h2>
  <?php if (isset($message)): ?>
    <div class="alert alert-info"><?= $message ?></div>
  <?php endif; ?>

  <form action="/halisaha/index.php?controller=participation&action=add" method="POST">
    <div class="form-group">
      <label for="match_id">Maç Seçin</label>
      <select name="match_id" id="match_id" class="form-control" required>
        <?php foreach ($matches as $match): ?>
          <option value="<?= $match['match_id'] ?>">
            <?= $match['match_date'] . " - " . $match['location'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <h4 class="mt-3">Oyuncular</h4>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Seç</th>
          <th>İsim</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($players as $player): ?>
          <tr>
            <td>
              <input type="checkbox" name="player_ids[]" value="<?= $player['player_id'] ?>">
            </td>
            <td><?= $player['name'] . " " . $player['surname'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <button type="submit" class="btn btn-primary">Katılımı Kaydet</button>
  </form>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
