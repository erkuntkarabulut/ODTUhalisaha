<?php include __DIR__ . '/../layout/header.php'; ?>
<?php include __DIR__ . '/../layout/navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Yeni Oyuncu Ekle</h2>

    <div class="card">
        <div class="card-body">
            <form action="/halisaha/index.php?controller=player&action=add" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Ad Soyad</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-posta</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="phone_number" class="form-label">Telefon</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control">
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success">Ekle</button>
                    <a href="/public/index.php?controller=player&action=index" class="btn btn-secondary">İptal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
