<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';
if (!isLoggedIn()) { redirect('auth/login.php'); }

$uid = $_SESSION['user_id'];
$totalPesanan = $conn->query("SELECT COUNT(*) as t FROM pesanan WHERE user_id=$uid")->fetch_assoc()['t'];
$voucherAktif = $conn->query("
    SELECT COUNT(*) as t
    FROM voucher v
    JOIN pesanan p ON v.pesanan_id=p.id
    WHERE p.user_id=$uid
      AND v.status!='expired'
      AND v.jumlah_digunakan < v.max_penggunaan
")->fetch_assoc()['t'];
$totalBayar = $conn->query("SELECT COALESCE(SUM(total_harga),0) as t FROM pesanan WHERE user_id=$uid AND status IN ('dibayar','digunakan')")->fetch_assoc()['t'];
$recentOrders = $conn->query("SELECT p.*, v.kode_voucher FROM pesanan p LEFT JOIN voucher v ON v.pesanan_id=p.id WHERE p.user_id=$uid ORDER BY p.created_at DESC LIMIT 5");
$currentPage = 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Dashboard | Orang Hutan Heaven</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>dashboard/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="dash-layout">
    <?php include 'sidebar.php'; ?>
    <main class="dash-main">
        <div class="dash-topbar">
            <h2>👋 Halo, <?= e($_SESSION['nama']) ?>!</h2>
            <a href="<?= BASE_URL ?>tiket/pesan.php" class="btn btn-primary btn-sm"><i class="fas fa-ticket"></i> Pesan Tiket</a>
        </div>
        <div class="dash-content">
            <?= getFlash() ?>
            <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
                <div class="stat-card"><div class="stat-icon"><i class="fas fa-receipt"></i></div><div class="stat-value"><?= $totalPesanan ?></div><div class="stat-label">Total Pesanan</div></div>
                <div class="stat-card"><div class="stat-icon"><i class="fas fa-ticket"></i></div><div class="stat-value"><?= $voucherAktif ?></div><div class="stat-label">Voucher Aktif</div></div>
                <div class="stat-card"><div class="stat-icon"><i class="fas fa-wallet"></i></div><div class="stat-value"><?= formatRupiah($totalBayar) ?></div><div class="stat-label">Total Pembelian</div></div>
            </div>
            <div class="dash-card">
                <div class="dash-card-header"><h3>📋 Pesanan Terbaru</h3><a href="pesanan-saya.php" class="btn btn-sm btn-outline">Semua</a></div>
                <div class="dash-card-body" style="padding:0;">
                    <table><thead><tr><th>Kode</th><th>Tanggal</th><th>Total</th><th>Voucher</th><th>Status</th></tr></thead><tbody>
                    <?php while($o = $recentOrders->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?= e($o['kode_pesanan']) ?></strong></td>
                        <td><?= date('d/m/Y', strtotime($o['tanggal_kunjungan'])) ?></td>
                        <td><?= formatRupiah($o['total_harga']) ?></td>
                        <td><code style="background:var(--gray-100);padding:3px 8px;border-radius:4px;font-weight:700;"><?= e($o['kode_voucher'] ?? '-') ?></code></td>
                        <td><span class="badge badge-<?= $o['status']=='dibayar'?'success':($o['status']=='digunakan'?'info':'warning') ?>"><?= $o['status'] ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody></table>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>script.js"></script>
</body>
</html>
