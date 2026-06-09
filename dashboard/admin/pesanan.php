<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';
if (!isLoggedIn() || !isAdmin()) { redirect('auth/login.php'); }

$pesanan = $conn->query("SELECT p.*, u.nama as user_nama, u.email as user_email, v.kode_voucher FROM pesanan p JOIN users u ON p.user_id=u.id LEFT JOIN voucher v ON v.pesanan_id=p.id ORDER BY p.created_at DESC");
$currentPage = 'pesanan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Pesanan | Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>dashboard/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="dash-layout">
    <?php include 'sidebar.php'; ?>
    <main class="dash-main">
        <div class="dash-topbar"><h2>📋 Semua Pesanan</h2></div>
        <div class="dash-content">
            <?= getFlash() ?>
            <div class="dash-card">
                <div class="dash-card-body" style="padding:0;">
                    <div class="table-responsive">
                    <table>
                        <thead><tr><th>Kode</th><th>Pembeli</th><th>Tanggal</th><th>Total</th><th>Voucher</th><th>Metode</th><th>Status</th><th>Waktu Pesan</th></tr></thead>
                        <tbody>
                        <?php while($p = $pesanan->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?= e($p['kode_pesanan']) ?></strong></td>
                            <td><?= e($p['user_nama']) ?><br><small style="color:var(--gray-500);"><?= e($p['user_email']) ?></small></td>
                            <td><?= date('d/m/Y', strtotime($p['tanggal_kunjungan'])) ?></td>
                            <td><strong><?= formatRupiah($p['total_harga']) ?></strong></td>
                            <td><code style="background:var(--gray-100);padding:3px 8px;border-radius:4px;font-weight:700;"><?= e($p['kode_voucher'] ?? '-') ?></code></td>
                            <td><code style="background:var(--gray-100);padding:3px 8px;border-radius:4px;font-weight:700;"><?= e($p['metode_bayar'] ?? '-') ?></code></td>
                            <td><span class="badge badge-<?= $p['status']=='dibayar'?'success':($p['status']=='digunakan'?'info':($p['status']=='pending'?'warning':'danger')) ?>"><?= e($p['status']) ?></span></td>
                            <td style="font-size:.8rem;"><?= timeAgo($p['created_at']) ?></td>
                        </tr>
                        <?php endwhile; ?>
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
