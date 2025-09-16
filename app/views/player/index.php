<?php include __DIR__ . '/../layout/header.php'; ?>
<?php include __DIR__ . '/../layout/navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Oyuncu Yönetimi</h2>

<!-- İşlem Sonucu Mesajı -->
<?php if (!empty($_GET['success']) || !empty($_GET['error'])): ?>
        <div id="actionMessage" class="alert 
            <?= !empty($_GET['success']) ? 'alert-success' : 'alert-danger' ?> 
            text-center shadow-lg">
            <span><?= htmlspecialchars($_GET['success'] ?? $_GET['error']) ?></span>
            <button type="button" class="btn-close float-end" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Oyuncu Ekleme Formu -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-center">Yeni Oyuncu Ekle</h5>
                </div>
                <div class="card-body">
                    <form id="addPlayerForm" action="/halisaha/index.php?controller=player&action=add" method="POST">
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
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-user-plus"></i> Ekle
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Oyuncu Listesi -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">Oyuncu Listesi</div>
                <div class="card-body">
                    <table id="playerTable" class="table table-bordered table-hover text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Ad Soyad</th>
                                <th>E-posta</th>
                                <th>Telefon</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($players as $player): ?>
                                <tr id="playerRow_<?= $player['player_id'] ?>">
                                    <td><?= $player['player_id'] ?></td>
                                    <td><?= htmlspecialchars($player['name']) ?></td>
                                    <td><?= htmlspecialchars($player['email']) ?></td>
                                    <td><?= htmlspecialchars($player['phone_number']) ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm editPlayerBtn"
                                            data-id="<?= $player['player_id'] ?>"
                                            data-name="<?= htmlspecialchars($player['name']) ?>"
                                            data-email="<?= htmlspecialchars($player['email']) ?>"
                                            data-phone="<?= htmlspecialchars($player['phone_number']) ?>"
                                            data-bs-toggle="modal" data-bs-target="#editPlayerModal">
                                            Güncelle
                                        </button>
                                        <button class="btn btn-danger btn-sm deletePlayerBtn"
                                            data-id="<?= $player['player_id'] ?>">
                                            Sil
                                        </button>
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

<!-- Oyuncu Güncelleme Modal -->
<div class="modal fade" id="editPlayerModal" tabindex="-1" aria-labelledby="editPlayerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPlayerModalLabel">Oyuncu Güncelle</h5>
                <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPlayerForm">
                    <input type="hidden" name="player_id" id="edit_player_id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Ad Soyad</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">E-posta</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone_number" class="form-label">Telefon</label>
                        <input type="text" name="phone_number" id="edit_phone_number" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Güncelle</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>

<script>
$(document).ready(function() {
    $('#playerTable').DataTable({
        "language": {
            "lengthMenu": "Sayfada _MENU_ kayıt göster",
            "zeroRecords": "Eşleşen oyuncu bulunamadı",
            "info": "Toplam _TOTAL_ oyuncudan _START_ - _END_ arası gösteriliyor",
            "infoEmpty": "Kayıt bulunamadı",
            "infoFiltered": "(_MAX_ oyuncudan filtrelendi)",
            "search": "Ara:",
            "paginate": {
                "first": "İlk",
                "last": "Son",
                "next": "Sonraki",
                "previous": "Önceki"
            }
        }
    });

    // Güncelleme butonuna tıklanınca popup içini doldur
    $(".editPlayerBtn").click(function() {
        $("#edit_player_id").val($(this).data('id'));
        $("#edit_name").val($(this).data('name'));
        $("#edit_email").val($(this).data('email'));
        $("#edit_phone_number").val($(this).data('phone'));
    });

    // Güncelleme işlemi AJAX ile sayfa yenilemeden yapılacak
    $("#editPlayerForm").submit(function(e) {
        e.preventDefault();
        $.post("/halisaha/index.php?controller=player&action=update", $(this).serialize(), function(response) {
            $("#editPlayerModal").modal("hide"); // Popup'ı kapat
            // Sayfayı mesaj ile birlikte yenile
            window.location.href = "/halisaha/index.php?controller=player&action=index&success=" + encodeURIComponent("Oyuncu başarıyla güncellendi.");

        });
    });

    // Oyuncu Silme AJAX
    $(".deletePlayerBtn").click(function() {
        let playerId = $(this).data('id');
        if (confirm("Bu oyuncuyu silmek istediğinizden emin misiniz?")) {
            $.get("/halisaha/index.php?controller=player&action=delete&id=" + playerId, function(response) {
                $("#playerRow_" + playerId).remove();
                showMessage("Oyuncu başarıyla silindi.");
            });
        }
    });

    // Başarı mesajını gösterme fonksiyonu
    function showMessage(message) {
        let messageBox = $("#actionMessage");
        $("#messageText").text(message);
        messageBox.removeClass("d-none alert-danger").addClass("alert-success").fadeIn();

        // 5 saniye sonra otomatik kapat
       // setTimeout(() => messageBox.fadeOut(), 5000);
    }

    // Kullanıcı mesajı manuel kapatabilsin
    $(".btn-close").click(function() {
        $("#actionMessage").fadeOut();
    });
});
</script>
