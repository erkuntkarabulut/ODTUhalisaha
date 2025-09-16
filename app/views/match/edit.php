<?php include __DIR__ . '/../layout/header.php'; ?>
<?php include __DIR__ . '/../layout/navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Maçı Düzenle</h2>

    <div class="card">
        <div class="card-body">
            <form action="/halisaha/index.php?controller=match&action=edit" method="POST">
                <input type="hidden" name="match_id" value="<?= $match['match_id'] ?>">

                <div class="mb-3">
                    <label for="match_date" class="form-label">Maç Tarihi</label>
                    <input type="date" name="match_date" id="match_date" class="form-control" value="<?= $match['match_date'] ?>" required>
                </div>

                <div class="mb-3">
                    <label for="match_time" class="form-label">Maç Saati</label>
                    <input type="time" name="match_time" id="match_time" class="form-control" value="<?= $match['match_time'] ?>" required>
                </div>

                <div class="mb-3">
                    <label for="location" class="form-label">Lokasyon</label>
                    <input type="text" name="location" id="location" class="form-control" value="<?= $match['location'] ?>" required>
                </div>

                <div class="mb-3">
                    <label for="match_fee" class="form-label">Ücret (₺)</label>
                    <input type="number" step="0.01" name="match_fee" id="match_fee" class="form-control" value="<?= $match['match_fee'] ?>" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success">Güncelle</button>
                    <a href="/public/index.php?controller=match&action=index" class="btn btn-secondary">İptal</a>
                </div>
            </
