<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
if (!isLoggedIn()) { setFlash('error','Silakan login terlebih dahulu.'); redirect('auth/login.php'); }

$tikets = $conn->query("SELECT * FROM kategori_tiket WHERE status='aktif'");
$pageTitle = 'Pesan Tiket';
$extraCss = BASE_URL . 'tiket/tiket.css';
require_once '../includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>🎫 Pesan Tiket</h1>
        <p>Pilih tanggal kunjungan dan kategori tiket Anda</p>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width:800px;">
        <?= getFlash() ?>
        <div class="card" style="padding:35px;">
            <form method="POST" action="<?= BASE_URL ?>tiket/proses_pesan.php" id="formPesan">
                <div class="form-group">
                    <label for="tanggal"><i class="fas fa-calendar"></i> Tanggal Kunjungan</label>
                    <input type="date" id="tanggal" name="tanggal_kunjungan" class="form-control" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                </div>

                <h3 style="margin:25px 0 15px;">Pilih Tiket</h3>
                <div id="tiketList">
                <?php while($t = $tikets->fetch_assoc()): ?>
                    <div class="tiket-row">
                        <div class="tiket-info">
                            <h4><?= e($t['nama']) ?></h4>
                            <p><?= e($t['deskripsi']) ?></p>
                            <span class="tiket-harga"><?= formatRupiah($t['harga']) ?></span>
                        </div>
                        <div class="tiket-qty">
                            <button type="button" class="qty-btn minus" data-id="<?= $t['id'] ?>">−</button>
                            <input type="number" name="tiket[<?= $t['id'] ?>]" id="qty_<?= $t['id'] ?>" value="0" min="0" class="qty-input" data-harga="<?= $t['harga'] ?>">
                            <button type="button" class="qty-btn plus" data-id="<?= $t['id'] ?>">+</button>
                        </div>
                    </div>
                <?php endwhile; ?>
                </div>

                <div class="form-group" style="margin-top:20px;">
                    <label for="metode"><i class="fas fa-credit-card"></i> Metode Pembayaran</label>
                    <select name="metode_bayar" id="metode" class="form-control" required>
                        <option value="">-- Pilih Metode --</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="E-Wallet">E-Wallet (GoPay/OVO/Dana)</option>
                        <option value="Kartu Kredit">Kartu Kredit</option>
                    </select>
                </div>

                <div class="total-box">
                    <div class="flex-between">
                        <span>Total Pembayaran:</span>
                        <span class="total-price" id="totalHarga">Rp 0</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;margin-top:20px;" id="btnPesan" disabled>
                    <i class="fas fa-shopping-cart"></i> Bayar & Pesan Tiket
                </button>
            </form>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const qtyInputs = document.querySelectorAll('.qty-input');
    const totalEl = document.getElementById('totalHarga');
    const btnPesan = document.getElementById('btnPesan');

    function updateTotal() {
        let total = 0;
        qtyInputs.forEach(input => {
            total += parseInt(input.value || 0) * parseFloat(input.dataset.harga);
        });
        totalEl.textContent = formatRupiah(total);
        btnPesan.disabled = total === 0;
    }

    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const input = document.getElementById('qty_' + id);
            let val = parseInt(input.value || 0);
            if (this.classList.contains('plus')) val++;
            if (this.classList.contains('minus') && val > 0) val--;
            input.value = val;
            updateTotal();
        });
    });

    qtyInputs.forEach(input => input.addEventListener('change', updateTotal));
});
</script>

<?php require_once '../includes/footer.php'; ?>
