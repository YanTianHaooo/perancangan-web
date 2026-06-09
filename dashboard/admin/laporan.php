<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';
if (!isLoggedIn() || !isAdmin()) { redirect('auth/login.php'); }

$monthly = $conn->query("SELECT DATE_FORMAT(created_at,'%Y-%m') as bulan, COUNT(*) as total_pesanan, SUM(total_harga) as pendapatan FROM pesanan WHERE status IN ('dibayar','digunakan') GROUP BY bulan ORDER BY bulan DESC LIMIT 12");

$stats = getStats($conn);
$currentPage = 'laporan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Laporan | Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>dashboard/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="dash-layout">
    <?php include 'sidebar.php'; ?>
    <main class="dash-main">
        <div class="dash-topbar"><h2>📊 Laporan Penjualan</h2></div>
        <div class="dash-content">
            <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
                <div class="stat-card"><div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div><div class="stat-value"><?= formatRupiah($stats['total_pendapatan']) ?></div><div class="stat-label">Total Pendapatan</div></div>
                <div class="stat-card"><div class="stat-icon"><i class="fas fa-calendar-day"></i></div><div class="stat-value"><?= formatRupiah($stats['pendapatan_hari_ini']) ?></div><div class="stat-label">Pendapatan Hari Ini</div></div>
                <div class="stat-card"><div class="stat-icon"><i class="fas fa-user-friends"></i></div><div class="stat-value"><?= $stats['total_users'] ?></div><div class="stat-label">Total Member</div></div>
            </div>
            <div class="dash-card">
                <div class="dash-card-header"><h3>📈 Pendapatan Per Bulan</h3></div>
                <div class="dash-card-body" style="padding:0;">
                    <div class="table-responsive">
                    <table>
                        <thead><tr><th>Bulan</th><th>Jumlah Pesanan</th><th>Total Pendapatan</th></tr></thead>
                        <tbody>
                        <?php while($m = $monthly->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?= date('F Y', strtotime($m['bulan'].'-01')) ?></strong></td>
                            <td><?= $m['total_pesanan'] ?> pesanan</td>
                            <td><strong><?= formatRupiah($m['pendapatan']) ?></strong></td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if ($monthly->num_rows == 0): ?>
                        <tr><td colspan="3" class="text-center" style="padding:30px;color:var(--gray-500);">Belum ada data penjualan</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>script.js"></script>
</body>
</html>
