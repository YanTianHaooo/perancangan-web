<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';
if (!isLoggedIn()) { redirect('auth/login.php'); }

$uid = $_SESSION['user_id'];
$pesanan = $conn->query("SELECT p.*, v.kode_voucher FROM pesanan p LEFT JOIN voucher v ON v.pesanan_id=p.id WHERE p.user_id=$uid ORDER BY p.created_at DESC");
$currentPage = 'pesanan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Pesanan Saya | Orang Hutan Heaven</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>dashboard/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="dash-layout">
    <?php include 'sidebar.php'; ?>
    <main class="dash-main">
        <div class="dash-topbar">
            <h2>📋 Pesanan Saya</h2>
            <a href="<?= BASE_URL ?>tiket/pesan.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Pesan Baru</a>
        </div>
        <div class="dash-content">
            <?= getFlash() ?>
            <?php if ($pesanan->num_rows > 0): ?>
            <div class="dash-card">
                <div class="dash-card-body" style="padding:0;">
                    <div class="table-responsive">
                    <table>
                        <thead><tr><th>Kode Pesanan</th><th>Tanggal Kunjungan</th><th>Total</th><th>Metode Bayar</th><th>Voucher</th><th>Status</th><th>Waktu Pesan</th></tr></thead>
                        <tbody>
                        <?php while($p = $pesanan->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?= e($p['kode_pesanan']) ?></strong></td>
                            <td><?= date('d F Y', strtotime($p['tanggal_kunjungan'])) ?></td>
                            <td><strong><?= formatRupiah($p['total_harga']) ?></strong></td>
                            <td><?= e($p['metode_bayar']) ?></td>
                            <td>
                                <?php if ($p['kode_voucher']): ?>
                                <code style="background:var(--gray-100);padding:4px 10px;border-radius:4px;font-weight:700;letter-spacing:2px;"><?= e($p['kode_voucher']) ?></code>
                                <?php else: ?>-<?php endif; ?>
                            </td>
                            <td><span class="badge badge-<?= $p['status']=='dibayar'?'success':($p['status']=='digunakan'?'info':($p['status']=='pending'?'warning':'danger')) ?>"><?= e($p['status']) ?></span></td>
                            <td style="font-size:.8rem;"><?= timeAgo($p['created_at']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="text-center" style="padding:60px 0;">
                <div style="font-size:4rem;margin-bottom:15px;">📭</div>
                <h3>Belum Ada Pesanan</h3>
                <p style="color:var(--gray-500);margin-bottom:20px;">Pesan tiket pertama Anda sekarang!</p>
                <a href="<?= BASE_URL ?>tiket/pesan.php" class="btn btn-primary">🎫 Pesan Tiket</a>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>script.js"></script>
</body>
</html>
