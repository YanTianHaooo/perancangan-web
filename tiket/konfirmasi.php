<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
if (!isLoggedIn()) { redirect('auth/login.php'); }

$pesananId = intval($_GET['id'] ?? 0);
if (!$pesananId) { redirect('dashboard/pembeli/pesanan-saya.php'); }

$stmt = $conn->prepare("SELECT p.*, u.nama as user_nama, u.email as user_email FROM pesanan p JOIN users u ON p.user_id=u.id WHERE p.id=? AND p.user_id=?");
$stmt->bind_param("ii", $pesananId, $_SESSION['user_id']);
$stmt->execute();
$pesanan = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$pesanan) { redirect('dashboard/'); }

$details = $conn->query("SELECT dp.*, kt.nama FROM detail_pesanan dp JOIN kategori_tiket kt ON dp.kategori_tiket_id=kt.id WHERE dp.pesanan_id=$pesananId");

$voucher = $conn->query("SELECT * FROM voucher WHERE pesanan_id=$pesananId")->fetch_assoc();

$pageTitle = 'Konfirmasi Pesanan';
$extraCss = BASE_URL . 'tiket/tiket.css';
require_once '../includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>✅ Pesanan Berhasil!</h1>
        <p>Simpan kode voucher Anda untuk digunakan saat berkunjung</p>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width:700px;">
        <div class="voucher-box" style="margin-bottom:30px;">
            <p style="font-size:.9rem;opacity:.8;margin-bottom:5px;">Kode Voucher Anda</p>
            <div class="voucher-code" id="voucherCode"><?= e($voucher['kode_voucher']) ?></div>
            <p style="font-size:.85rem;opacity:.7;">Gunakan kode ini di Buku Tamu saat Anda berkunjung</p>
            <button onclick="copyVoucher()" class="btn btn-secondary" style="margin-top:15px;">
                <i class="fas fa-copy"></i> Salin Kode
            </button>
        </div>

        <!-- Detail Pesanan -->
        <div class="card" style="padding:30px;">
            <h3 style="margin-bottom:20px;">📋 Detail Pesanan</h3>
            <table style="width:100%;">
                <tr><td style="color:var(--gray-500);padding:8px 0;">Kode Pesanan</td><td style="text-align:right;font-weight:600;padding:8px 0;"><?= e($pesanan['kode_pesanan']) ?></td></tr>
                <tr><td style="color:var(--gray-500);padding:8px 0;">Tanggal Kunjungan</td><td style="text-align:right;font-weight:600;padding:8px 0;"><?= date('d F Y', strtotime($pesanan['tanggal_kunjungan'])) ?></td></tr>
                <tr><td style="color:var(--gray-500);padding:8px 0;">Metode Bayar</td><td style="text-align:right;padding:8px 0;"><?= e($pesanan['metode_bayar']) ?></td></tr>
                <tr><td style="color:var(--gray-500);padding:8px 0;">Status</td><td style="text-align:right;padding:8px 0;"><span class="badge badge-success">Dibayar</span></td></tr>
            </table>
            
            <hr style="margin:20px 0;border:none;border-top:1px solid var(--gray-200);">
            <h4 style="margin-bottom:10px;">Rincian Tiket</h4>
            <?php while($d = $details->fetch_assoc()): ?>
            <div class="flex-between" style="padding:8px 0;">
                <span><?= e($d['nama']) ?> × <?= $d['jumlah'] ?></span>
                <span style="font-weight:600;"><?= formatRupiah($d['subtotal']) ?></span>
            </div>
            <?php endwhile; ?>
            <hr style="margin:15px 0;border:none;border-top:2px solid var(--gray-800);">
            <div class="flex-between">
                <span style="font-weight:700;font-size:1.1rem;">Total</span>
                <span style="font-weight:800;font-size:1.3rem;color:var(--primary);"><?= formatRupiah($pesanan['total_harga']) ?></span>
            </div>
        </div>

        <div style="text-align:center;margin-top:25px;display:flex;gap:15px;justify-content:center;flex-wrap:wrap;">
            <a href="<?= BASE_URL ?>dashboard/pembeli/pesanan-saya.php" class="btn btn-success"><i class="fas fa-list"></i> Pesanan Saya</a>
            <a href="<?= BASE_URL ?>buku-tamu/" class="btn btn-outline"><i class="fas fa-book"></i> Isi Buku Tamu</a>
            <a href="<?= BASE_URL ?>" class="btn btn-secondary" style="border-color:var(--gray-300);color:var(--gray-700);">Kembali ke Home</a>
        </div>
    </div>
</section>

<script>
function copyVoucher() {
    const code = document.getElementById('voucherCode').textContent;
    navigator.clipboard.writeText(code).then(() => {
        alert('Kode voucher "' + code + '" berhasil disalin!');
    });
}
</script>

<?php require_once '../includes/footer.php'; ?>
