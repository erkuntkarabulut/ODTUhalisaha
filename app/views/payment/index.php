<?php include __DIR__ . '/../layout/header.php'; ?>
<?php include __DIR__ . '/../layout/navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Ödeme Yönetimi</h2>

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
        <!-- Ödeme Ekleme Formu -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Yeni Ödeme Ekle</h5>
                </div>
                <div class="card-body">
                    <form id="addPaymentForm" action="/halisaha/index.php?controller=payment&action=add" method="POST">
                        <div class="mb-3">
                            <label for="player_id" class="form-label">Oyuncu Seçin</label>
                            <select name="player_id" id="player_id" class="form-select" required>
                                <option value="" disabled selected>Oyuncu Seçiniz...</option>
                                <?php foreach ($players as $player): ?>
                                    <option value="<?= $player['player_id'] ?>">
                                        <?= htmlspecialchars($player['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Ödenen Tutar (₺)</label>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Ödeme Tarihi</label>
                            <input type="date" name="payment_date" id="payment_date" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-money-bill-wave"></i> Ödeme Ekle
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ödeme Listesi -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white text-center">
                    <h5 class="mb-0">Ödeme Listesi</h5>
                </div>
                <div class="card-body">
                    <table id="paymentTable" class="table table-bordered table-hover text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Oyuncu</th>
                                <th>Tutar (₺)</th>
                                <th>Tarih</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $payment): ?>
                                <tr id="paymentRow_<?= $payment['payment_id'] ?>">
                                    <td><?= $payment['payment_id'] ?></td>
                                    <td><?= htmlspecialchars($payment['player_name']) ?></td>
                                    <td><?= number_format($payment['amount'], 2) ?> ₺</td>
                                    <td><?= $payment['payment_date'] ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm editPaymentBtn"
                                            data-id="<?= $payment['payment_id'] ?>"
                                            data-name="<?= $payment['player_name'] ?>"
                                            data-player-id="<?= $payment['player_id'] ?>"
                                            data-amount="<?= $payment['amount'] ?>"
                                            data-date="<?= date('Y-m-d', strtotime($payment['payment_date'])) ?>"
                                            data-bs-toggle="modal" data-bs-target="#editPaymentModal">
                                            Güncelle
                                        </button>
                                        <button class="btn btn-danger btn-sm deletePaymentBtn"
                                            data-id="<?= $payment['payment_id'] ?>">
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

<!-- Ödeme Güncelleme Modal -->
<div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPaymentModalLabel">Ödeme Güncelle</h5>
                <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPaymentForm" action="/halisaha/index.php?controller=payment&action=update" method="POST">
                    <input type="hidden" name="payment_id" id="edit_payment_id">
                    <input type="hidden" name="player_id" id="edit_player_id">
                    <div class="mb-3">
                        <label class="form-label"><strong>Adı:</strong></label>
                        <label class="form-label" id="edit_name"></label>
                    </div>
                    <div class="mb-3">
                        <label for="edit_amount" class="form-label">Ödenen Tutar (₺)</label>
                        <input type="number" step="0.01" name="amount" id="edit_amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_payment_date" class="form-label">Ödeme Tarihi</label>
                        <input type="date" name="payment_date" id="edit_payment_date" class="form-control" required>
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
    $('#paymentTable').DataTable({
        "language": {
            "lengthMenu": "Sayfada _MENU_ kayıt göster",
            "zeroRecords": "Ödeme bulunamadı",
            "info": "Toplam _TOTAL_ ödemeden _START_ - _END_ arası gösteriliyor",
            "infoEmpty": "Ödeme bulunamadı",
            "infoFiltered": "(_MAX_ ödemeden filtrelendi)",
            "search": "Ara:",
            "paginate": {
                "first": "İlk",
                "last": "Son",
                "next": "Sonraki",
                "previous": "Önceki"
            }
        }
    });

    $(".editPaymentBtn").click(function() {
        let paymentId = $(this).data('id');
        let player_id= $(this).data('player-id');
        let amount= $(this).data('amount');
        let name= $(this).data('name');
        let paymentDate = $(this).data('date');

        // Tarih formatını düzgün aktarmak için güncelleme yap
        let formattedDate = new Date(paymentDate).toISOString().split('T')[0];

        $("#edit_payment_id").val(paymentId);
        $("#edit_player_id").val(player_id);
        $("#edit_amount").val(amount);
        $("#edit_name").html(name);
        $("#edit_payment_date").val(formattedDate);
    });

    $("#editPaymentForm").submit(function(e) {
        e.preventDefault();
        $.post("/halisaha/index.php?controller=payment&action=update", $(this).serialize(), function(response) {
            $("#editPaymentModal").modal("hide");

            // Sayfayı yenile ve mesajı URL ile ekleyerek gönder
            window.location.href = "/halisaha/index.php?controller=payment&action=index&success=" + encodeURIComponent("Ödeme başarıyla güncellendi.");
        });
    });

    $(".deletePaymentBtn").click(function() {
        let paymentId = $(this).data('id');
        if (confirm("Bu ödemeyi silmek istediğinizden emin misiniz?")) {
            $.get("/halisaha/index.php?controller=payment&action=delete&id=" + paymentId, function(response) {
                $("#paymentRow_" + paymentId).remove();
                showMessage("Ödeme başarıyla silindi.");
            });
        }
    });

    function showMessage(message) {
        let messageBox = $("#actionMessage");
        $("#messageText").text(message);
        messageBox.removeClass("d-none alert-danger").addClass("alert-success").fadeIn();
    }

    $(".btn-close").click(function() {
        $("#actionMessage").fadeOut();
    });
});
</script>
