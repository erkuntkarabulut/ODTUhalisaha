<?php include __DIR__ . '/../layout/header.php'; ?>
<?php include __DIR__ . '/../layout/navbar.php'; ?>
<div class="container mt-4">
  <h2>Ödeme Kaydı Ekle</h2>
  <?php if (isset($message)): ?>
    <div class="alert alert-info"><?= $message ?></div>
  <?php endif; ?>
  <form action="/halisaha/index.php?controller=payment&action=add" method="POST">
    <div class="form-group">
      <label for="player_id">Oyuncu Seçin</label>
      <select name="player_id" id="player_id" class="form-control" required>
        <?php foreach ($players as $player): ?>
          <option value="<?= $player['player_id'] ?>">
            <?= $player['name'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label for="amount">Ödeme Miktarı</label>
      <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Ödemeyi Kaydet</button>
  </form>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>

