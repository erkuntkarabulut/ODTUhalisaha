<?php include __DIR__ . '/../layout/header.php'; ?>
<?php include __DIR__ . '/../layout/navbar.php'; ?>
<div class="container mt-4">
  <h2>Maç Ekle</h2>
  <?php if (isset($message)): ?>
    <div class="alert alert-info"><?= $message ?></div>
  <?php endif; ?>
  <form action="/halisaha/index.php?controller=match&action=add" method="POST">
    <div class="form-group">
      <label for="match_date">Maç Tarihi</label>
      <input type="date" name="match_date" id="match_date" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="match_time">Maç Saati</label>
      <input type="time" name="match_time" id="match_time" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="location">Lokasyon</label>
      <input type="text" name="location" id="location" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="match_fee">Maç Ücreti</label>
      <input type="number" step="0.01" name="match_fee" id="match_fee" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Ekle</button>
  </form>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>

