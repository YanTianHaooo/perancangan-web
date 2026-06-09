<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';
if (!isLoggedIn() || !isAdmin()) { redirect('auth/login.php' ); }

$users = $conn->query("
    SELECT u.*, 
    (SELECT COUNT(*) FROM pesanan WHERE user_id = u.id) AS total_pesanan 
    FROM users u 
    ORDER BY u.id ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Users | Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>dashboard/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="dash-layout">
    <?php include 'sidebar.php'; ?>
    <main class="dash-main">
        <div class="dash-topbar"><h2>👥 Kelola Users</h2></div>
        <div class="dash-content">
            <div class="dash-card">
                <div class="dash-card-body" style="padding:0;">
                    <div class="table-responsive">
                    <table>
                        <thead><tr><th>ID</th><th>Nama</th><th>Email</th><th>Telepon</th><th>Role</th><th>Pesanan</th><th>Bergabung</th></tr></thead>
                        <tbody>
                        <?php while($u = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><strong><?= e($u['nama']) ?></strong></td>
                            <td><?= e($u['email']) ?></td>
                            <td><?= e($u['telepon'] ?: '-') ?></td>
                            <td><span class="badge badge-<?= $u['role']=='admin'?'danger':'primary' ?>"><?= $u['role'] ?></span></td>
                            <td><?= $u['total_pesanan'] ?></td>
                            <td style="font-size:.8rem;"><?= timeAgo($u['created_at']) ?></td>
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
