<?php include __DIR__ . '/../layout/header.php'; ?>
<?php include __DIR__ . '/../layout/navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Oyuncu Düzenle</h2>

    <div class="card">
        <div class="card-body">
            <form action="/halisaha/index.php?controller=player&action=edit" method="POST">
                <input type="hidden" name="player_id" value="<?= $player['player_id'] ?>">

                <div class="mb-3">
                    <label for="name" class="form-label">Ad Soyad</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?= $player['name'] ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-posta</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= $player['email'] ?>" required>
                </div>

                <div class="mb-3">
                    <label for="phone_number" class="form-label">Telefon</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?= $player['phone_number'] ?>">
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success">Güncelle</button>
                    <a href="/public/index.php?controller=player&action=index" class="btn btn-secondary">İptal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
