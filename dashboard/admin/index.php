<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';
if (!isLoggedIn() || !isAdmin()) { redirect('auth/login.php'); }

$stats = getStats($conn);
$recentOrders = $conn->query("SELECT p.*, u.nama as user_nama FROM pesanan p JOIN users u ON p.user_id=u.id ORDER BY p.created_at DESC LIMIT 5");
$recentGuests = $conn->query("SELECT bt.*, v.kode_voucher FROM buku_tamu bt JOIN voucher v ON bt.voucher_id=v.id ORDER BY bt.created_at DESC LIMIT 5");
$currentPage = 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Admin Dashboard | Orang Hutan Heaven</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>dashboard/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="dash-layout">
    <?php include 'sidebar.php'; ?>
    <main class="dash-main">
        <div class="dash-topbar">
            <h2>📊 Dashboard</h2>
            <span style="color:var(--gray-500);font-size:.85rem;"><?= date('l, d F Y') ?></span>
        </div>
        <div class="dash-content">
            <?= getFlash() ?>
            <div class="stats-grid">
                <div class="stat-card"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-value"><?= $stats['total_pengunjung'] ?></div><div class="stat-label">Total Pengunjung</div></div>
                <div class="stat-card"><div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div><div class="stat-value"><?= formatRupiah($stats['total_pendapatan']) ?></div><div class="stat-label">Total Pendapatan</div></div>
                <div class="stat-card"><div class="stat-icon"><i class="fas fa-receipt"></i></div><div class="stat-value"><?= $stats['total_pesanan'] ?></div><div class="stat-label">Total Pesanan</div></div>
                <div class="stat-card"><div class="stat-icon"><i class="fas fa-ticket"></i></div><div class="stat-value"><?= $stats['voucher_aktif'] ?></div><div class="stat-label">Voucher Aktif</div></div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="dash-card">
                    <div class="dash-card-header"><h3>📋 Pesanan Terbaru</h3><a href="pesanan.php" class="btn btn-sm btn-outline">Semua</a></div>
                    <div class="dash-card-body" style="padding:0;">
                        <table><thead><tr><th>Kode</th><th>Pembeli</th><th>Total</th><th>Status</th></tr></thead><tbody>
                        <?php while($o = $recentOrders->fetch_assoc()): ?>
                        <tr><td><strong><?= e($o['kode_pesanan']) ?></strong></td><td><?= e($o['user_nama']) ?></td><td><?= formatRupiah($o['total_harga']) ?></td><td><span class="badge badge-<?= $o['status']=='dibayar'?'success':($o['status']=='pending'?'warning':'info') ?>"><?= $o['status'] ?></span></td></tr>
                        <?php endwhile; ?>
                        </tbody></table>
                    </div>
                </div>
                <div class="dash-card">
                    <div class="dash-card-header"><h3>📖 Buku Tamu Terbaru</h3><a href="buku-tamu.php" class="btn btn-sm btn-outline">Semua</a></div>
                    <div class="dash-card-body" style="padding:0;">
                        <table><thead><tr><th>Nama</th><th>Rating</th><th>Waktu</th></tr></thead><tbody>
                        <?php while($g = $recentGuests->fetch_assoc()): ?>
                        <tr><td><strong><?= e($g['nama_pengunjung']) ?></strong></td><td><?= renderStars($g['rating']) ?></td><td style="font-size:.8rem;"><?= timeAgo($g['created_at']) ?></td></tr>
                        <?php endwhile; ?>
                        </tbody></table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>script.js"></script>
</body>
</html>
