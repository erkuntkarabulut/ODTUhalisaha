<?php include __DIR__ . '/../layout/header.php'; ?>
<?php include __DIR__ . '/../layout/navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Katılım Kaydı</h2>

    <div class="row">
        <!-- Maç Ekleme Formu (Solda) -->
        <div class="col-md-4">
            <?php if (isset($_GET['message'])): ?>
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-circle-fill" style="font-size: 2rem; margin-right: 10px;"></i>
                    <span><?= htmlspecialchars($_GET['message']) ?></span>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header bg-primary text-white">Yeni Maç Ekle</div>
                <div class="card-body">
                    <form action="/halisaha/index.php?controller=participation&action=add" method="POST">
                        <div class="mb-3">
                            <label for="match_date" class="form-label">Maç Tarihi</label>
                            <input type="date" name="match_date" id="match_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="match_time" class="form-label">Maç Saati</label>
                            <input type="time" name="match_time" id="match_time" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Lokasyon</label>
                            <select name="location" id="location" class="form-select" required>
                                <option value="" disabled selected>Halısaha Seçiniz...</option>
                                <option value="halisaha1">Halı Saha 1</option>
                                <option value="halisaha2">Halı Saha 2</option>
                                <option value="halisaha3">Halı Saha 3</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="match_fee" class="form-label">Maç Ücreti (₺)</label>
                            <input type="number" step="0.01" name="match_fee" id="match_fee" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Oyuncu Seçme (Sağda) -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">Oyuncu Seçimi (Max 14)</div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($players as $player): ?>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input player-checkbox" type="checkbox" name="player_ids[]" value="<?= $player['player_id'] ?>">
                                    <label class="form-check-label">
                                        <?= $player['name'] ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll(".player-checkbox");
    let selectedCount = 0;

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener("change", function () {
            if (checkbox.checked) {
                selectedCount++;
            } else {
                selectedCount--;
            }

            if (selectedCount > 14) {
                alert("En fazla 14 oyuncu seçebilirsiniz!");
                checkbox.checked = false;
                selectedCount--;
            }
        });
    });
});
</script>
