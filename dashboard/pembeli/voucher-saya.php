<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';
if (!isLoggedIn()) { redirect('auth/login.php'); }

$uid = $_SESSION['user_id'];
$vouchers = $conn->query("
    SELECT 
        v.*,
        p.kode_pesanan,
        p.tanggal_kunjungan
    FROM voucher v
    JOIN pesanan p ON v.pesanan_id=p.id
    WHERE p.user_id=$uid
    ORDER BY v.created_at DESC
");
$currentPage = 'voucher';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Voucher Saya | Orang Hutan Heaven</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>dashboard/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="dash-layout">
    <?php include 'sidebar.php'; ?>
    <main class="dash-main">
        <div class="dash-topbar"><h2>🎫 Voucher Saya</h2></div>
        <div class="dash-content">
            <?php if ($vouchers->num_rows > 0): ?>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px;">
                <?php while($v = $vouchers->fetch_assoc()): ?>
                <?php
                    $totalJatah = max(1, (int)$v['max_penggunaan']);
                    $terpakai = (int)$v['jumlah_digunakan'];
                    $sisa = max(0, $totalJatah - $terpakai);
                    $masihAktif = ($v['status'] != 'expired' && $sisa > 0);
                ?>
                <div class="voucher-box" style="<?= !$masihAktif?'opacity:.6;':''; ?>">
                    <p style="font-size:.8rem;opacity:.7;">Kode Voucher</p>
                    <div class="voucher-code" style="font-size:2rem;letter-spacing:6px;"><?= e($v['kode_voucher']) ?></div>
                    <div style="margin-top:10px;font-size:.85rem;">
                        <p>Pesanan: <?= e($v['kode_pesanan']) ?></p>
                        <p>Kunjungan: <?= date('d/m/Y', strtotime($v['tanggal_kunjungan'])) ?></p>
                        <p>Jatah pakai: <?= $terpakai ?> / <?= $totalJatah ?> | Sisa: <?= $sisa ?></p>
                        <span class="badge badge-<?= $masihAktif?'success':($v['status']=='expired'?'danger':'info') ?>" style="margin-top:8px;"><?= $masihAktif ? 'AKTIF' : strtoupper($v['status']) ?></span>
                    </div>
                    <?php if ($masihAktif): ?>
                    <a href="<?= BASE_URL ?>buku-tamu/" class="btn btn-secondary" style="margin-top:12px;font-size:.85rem;">📖 Gunakan di Buku Tamu</a>
                    <?php endif; ?>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <div class="text-center" style="padding:60px 0;">
                <div style="font-size:4rem;margin-bottom:15px;">🎫</div>
                <h3>Belum Ada Voucher</h3>
                <p style="color:var(--gray-500);margin-bottom:20px;">Pesan tiket untuk mendapatkan kode voucher.</p>
                <a href="<?= BASE_URL ?>tiket/pesan.php" class="btn btn-primary">Pesan Tiket</a>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>script.js"></script>
</body>
</html>
