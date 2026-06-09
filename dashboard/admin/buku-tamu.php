<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';
if (!isLoggedIn() || !isAdmin()) { redirect('auth/login.php'); }

$entries = $conn->query("SELECT bt.*, v.kode_voucher, p.kode_pesanan FROM buku_tamu bt JOIN voucher v ON bt.voucher_id=v.id JOIN pesanan p ON v.pesanan_id=p.id ORDER BY bt.created_at DESC");
$currentPage = 'bukutamu';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Buku Tamu | Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>dashboard/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="dash-layout">
    <?php include 'sidebar.php'; ?>
    <main class="dash-main">
        <div class="dash-topbar"><h2>📖 Buku Tamu</h2></div>
        <div class="dash-content">
            <div class="dash-card">
                <div class="dash-card-body" style="padding:0;">
                    <div class="table-responsive">
                    <table>
                        <thead><tr><th>Nama</th><th>Voucher</th><th>Kode Pesanan</th><th>Rating</th><th>Pesan</th><th>Waktu</th></tr></thead>
                        <tbody>
                        <?php while($bt = $entries->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?= e($bt['nama_pengunjung']) ?></strong></td>
                            <td><code><?= e($bt['kode_voucher']) ?></code></td>
                            <td><?= e($bt['kode_pesanan']) ?></td>
                            <td><?= renderStars($bt['rating']) ?></td>
                            <td style="max-width:200px;"><?= e(substr($bt['pesan'],0,80)) ?></td>
                            <td style="font-size:.8rem;"><?= timeAgo($bt['created_at']) ?></td>
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
