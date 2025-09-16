<?php include __DIR__ . '/../layout/header.php'; ?>
<?php include __DIR__ . '/../layout/navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Dashboard 2</h2>

    <!-- Başarı & Hata Mesajları -->
    <?php if (!empty($_GET['error']) || !empty($_GET['success'])): ?>
        <div class="container mt-3">
            <div class="alert alert-<?= !empty($_GET['error']) ? 'danger' : 'success' ?> alert-dismissible fade show text-center shadow-lg" role="alert">
                <i class="fas fa-<?= !empty($_GET['error']) ? 'exclamation-circle' : 'check-circle' ?>"></i> 
                <strong><?= !empty($_GET['error']) ? 'Hata!' : 'Başarılı!' ?></strong> 
                <?= htmlspecialchars(!empty($_GET['error']) ? $_GET['error'] : $_GET['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Kasa Tutarı Kartı -->
        <div class="col-md-12 mb-4">
        <div class="row">
        <!-- Kasa Tutarı -->
        <div class="col-md-4">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h5 class="mb-0">Kasa Tutarı</h5>
                </div>
                <div class="card-body text-center">
                    <div id="kasaTutar" class="rounded shadow p-4 text-white">
                        <h1 id="kasaMiktar" class="display-4"><?= number_format($cashBalance, 2); ?> TL</h1>
                        <div id="kasaDurum" class="fs-4 fw-bold"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Borçlu Oyuncular -->
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-danger text-white text-center">
                    <h5 class="mb-0">Borçlu Oyuncular</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php if (!empty($negativePlayers)): ?>
                            <?php 
                            $chunks = array_chunk($negativePlayers, ceil(count($negativePlayers) / 3)); // En fazla 3 sütuna böl
                            foreach ($chunks as $chunk): ?>
                                <div class="col-md-4">
                                    <table class="table table-sm table-bordered text-center">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Oyuncu</th>
                                                <th>Bakiye (₺)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($chunk as $player): ?>
                                                <tr>
                                                    <td style="font-size: 14px;"><?= htmlspecialchars($player['name']); ?></td>
                                                    <td class="text-danger fw-bold" style="font-size: 14px;"><?= number_format($player['balance'], 2); ?> TL</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted">Borçlu oyuncu bulunmamaktadır.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>

        <!-- Maç Ekleme Formu -->
        <div class="col-md-12 mb-4">
            <div class="card shadow-lg">
                <div class="card-header bg-warning text-white text-center">
                    <h5 class="mb-0">Yeni Maç Ekle</h5>
                </div>
                <div class="card-body">
                    <form action="/halisaha/index.php?controller=match&action=add" method="POST" class="row g-3" onsubmit="return validateForm()">
                        <div class="col-md-2">
                            <label for="match_date" class="form-label">Maç Tarihi</label>
                            <input type="date" name="match_date" id="match_date" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label for="match_time" class="form-label">Maç Saati</label>
                            <input type="time" name="match_time" id="match_time" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="location" class="form-label">Lokasyon</label>
                            <select name="location" id="location" class="form-select" required>
                                <option value="" disabled selected>Halısaha Seçiniz...</option>
                                <option value="halisaha1">Halı Saha 1</option>
                                <option value="halisaha2">Halı Saha 2</option>
                                <option value="halisaha3">Halı Saha 3</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="match_fee" class="form-label">Maç Ücreti (₺)</label>
                            <input type="number" step="0.01" name="match_fee" id="match_fee" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="match_payer" class="form-label">Ödemeyi Yapan</label>
                            <select name="match_payer" id="match_payer" class="form-select" required>
                                <option value="" disabled selected>Oyuncu Seçiniz...</option>
                                <?php foreach ($players as $player): ?>
                                    <option value="<?= $player['player_id']; ?>">
                                        <?= htmlspecialchars($player['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Oyuncu Seçim Alanı -->
                        <div class="col-md-12">
                            <p id="selectedCount" class="text-center badge bg-light text-dark">Seçili Oyuncu Sayısı: 0</p>
                            <div class="row">
                                <?php foreach ($playerChunks as $chunk): ?>
                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Seç</th>
                                                    <th>Ad Soyad</th>
                                                    <th>Bakiye</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($chunk as $player): ?>
                                                    <tr>
                                                        <td><input class="player-checkbox" type="checkbox" name="players[]" value="<?= $player['player_id']; ?>"></td>
                                                        <td><?= htmlspecialchars($player['name']); ?></td>
                                                        <td>
                                                            <span class="badge <?= $player['balance'] >= 0 ? 'bg-success' : 'bg-danger' ?>">
                                                                <?= number_format($player['balance'], 2); ?> TL
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="col-12 text-center mt-3">
                            <button type="submit" class="btn btn-primary px-4">Maçı Ekle</button>
                            <button type="reset" class="btn btn-secondary px-4">Temizle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>

<!-- Form Doğrulama ve Oyuncu Sayaç -->
<script>
function validateForm() {
    const checkboxes = document.querySelectorAll('input[name="players[]"]:checked');
    if (checkboxes.length < 4) {
        alert('En az 4 oyuncu seçmelisiniz.');
        return false;
    }
    return true;
}

$(document).ready(function () {
    function updateCounter() {
        let selectedCount = $(".player-checkbox:checked").length;
        let countText = "Seçili Oyuncu Sayısı: " + selectedCount;
        $("#selectedCount").text(countText);
    }

    $(".player-checkbox").on("change", function () {
        updateCounter();
    });

    updateCounter();
});
</script>

<script>
        $(document).ready(function() {
            var kasaTutar = parseFloat($("#kasaMiktar").text());

            if (kasaTutar < 330) {
                $("#kasaTutar").addClass("kritik");
                $("#kasaDurum").text("Kritik!");
            } else if (kasaTutar >= 330 && kasaTutar <= 660) {
                $("#kasaTutar").addClass("ortalama");
                $("#kasaDurum").text("Ortalama");
            } else {
                $("#kasaTutar").addClass("rahat");
                $("#kasaDurum").text("Rahat");
            }
        });
    </script>
