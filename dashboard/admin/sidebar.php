<!-- Admin Sidebar Include -->
<aside class="dash-sidebar" id="sidebar">
    <div class="dash-sidebar-header">
        <div class="dash-avatar">🦧</div>
        <h3><?= e($_SESSION['nama']) ?></h3>
        <p>Administrator</p>
    </div>
    <nav class="dash-nav">
        <div class="dash-nav-label">Menu Utama</div>
        <a href="<?= BASE_URL ?>dashboard/admin/" class="<?= $currentPage=='dashboard'?'active':'' ?>"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>dashboard/admin/pesanan.php" class="<?= $currentPage=='pesanan'?'active':'' ?>"><i class="fas fa-receipt"></i> Pesanan</a>
        <a href="<?= BASE_URL ?>dashboard/admin/kelola-tiket.php" class="<?= $currentPage=='tiket'?'active':'' ?>"><i class="fas fa-ticket"></i> Kelola Tiket</a>
        <a href="<?= BASE_URL ?>dashboard/admin/orangutan.php" class="<?= $currentPage=='orangutan'?'active':'' ?>"><i class="fas fa-paw"></i> Data Orangutan</a>
        <div class="dash-nav-label">Lainnya</div>
        <a href="<?= BASE_URL ?>dashboard/admin/buku-tamu.php" class="<?= $currentPage=='bukutamu'?'active':'' ?>"><i class="fas fa-book"></i> Buku Tamu</a>
        <a href="<?= BASE_URL ?>dashboard/admin/users.php" class="<?= $currentPage=='users'?'active':'' ?>"><i class="fas fa-users"></i> Users</a>
        <a href="<?= BASE_URL ?>dashboard/admin/laporan.php" class="<?= $currentPage=='laporan'?'active':'' ?>"><i class="fas fa-chart-bar"></i> Laporan</a>
        <div class="dash-nav-label">Akun</div>
        <a href="<?= BASE_URL ?>"><i class="fas fa-home"></i> Ke Website</a>
        <a href="#" id="toggleTheme" class="theme-toggle-link"><i class="fas fa-moon"></i> Ganti Tema</a>
        <a href="<?= BASE_URL ?>auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
</aside>
