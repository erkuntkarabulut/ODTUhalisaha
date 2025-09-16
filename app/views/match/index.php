<?php include __DIR__ . '/../layout/header.php'; ?>
<?php include __DIR__ . '/../layout/navbar.php'; ?>



<div class="container mt-4">
    <h2 class="text-center mb-4">Maç Yönetimi</h2>
    <div class="row">
        <!-- Bilgilendirme Mesajı -->
        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>

        <!-- Maç Ekle Formu -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Yeni Maç Ekle</h5>
                </div>
                <div class="card-body">
                    <form action="/halisaha/index.php?controller=match&action=add" method="POST" class="row g-3">
                        <div class="col-md-6">
                            <label for="match_date" class="form-label">Maç Tarihi</label>
                            <input type="date" name="match_date" id="match_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="match_time" class="form-label">Maç Saati</label>
                            <input type="time" name="match_time" id="match_time" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="location" class="form-label">Lokasyon</label>
                            <select name="location" id="location" class="form-select" required>
                                <option value="" disabled selected>Halısaha Seçiniz...</option>
                                <option value="halisaha1">Halı Saha 1</option>
                                <option value="halisaha2">Halı Saha 2</option>
                                <option value="halisaha3">Halı Saha 3</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="match_fee" class="form-label">Maç Ücreti (₺)</label>
                            <input type="number" step="0.01" name="match_fee" id="match_fee" class="form-control" required>
                        </div>
                        <div class="col-12 text-center mt-3">
                            <button type="submit" class="btn btn-primary px-4">Maçı Ekle</button>
                            <button type="reset" class="btn btn-secondary px-4">Temizle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
        <!-- Mevcut Maçlar - DataTable -->
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Mevcut Maçlar</h5>
                </div>
                <div class="card-body">
                    <table id="matchTable" class="table table-bordered table-hover text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Tarih</th>
                                <th>Saati</th>
                                <th>Lokasyon</th>
                                <th>Ücret</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($matches as $match): ?>
                                <tr>
                                    <td><?= $match['match_id'] ?></td>
                                    <td><?= $match['match_date'] ?></td>
                                    <td><?= $match['match_time'] ?></td>
                                    <td><?= $match['location'] ?></td>
                                    <td><?= $match['match_fee'] ?> ₺</td>
                                    <td>
                                        <a href="/public/index.php?controller=match&action=delete&id=<?= $match['match_id'] ?>" class="btn btn-info btn-sm">Detay</a>
                                        <a href="/public/index.php?controller=match&action=edit&id=<?= $match['match_id'] ?>" class="btn btn-warning btn-sm">Düzenle</a>
                                        <a href="/public/index.php?controller=match&action=delete&id=<?= $match['match_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bu maçı silmek istediğinizden emin misiniz?');">Sil</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
<script>
$(document).ready(function() {
    $('#matchTable').DataTable({
        "language": {
            "lengthMenu": "Sayfada _MENU_ kayıt göster",
            "zeroRecords": "Eşleşen maç bulunamadı",
            "info": "Toplam _TOTAL_ maçtan _START_ - _END_ arası gösteriliyor",
            "infoEmpty": "Maç bulunamadı",
            "infoFiltered": "(_MAX_ maçtan filtrelendi)",
            "search": "Ara:",
            "paginate": {
                "first": "İlk",
                "last": "Son",
                "next": "Sonraki",
                "previous": "Önceki"
            },
        }
    });
});
</script>


