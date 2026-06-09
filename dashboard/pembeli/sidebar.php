<!-- Buyer Sidebar Include -->
<aside class="dash-sidebar" id="sidebar">
    <div class="dash-sidebar-header">
        <div class="dash-avatar" style="background:var(--primary);">👤</div>
        <h3><?= e($_SESSION['nama']) ?></h3>
        <p>Pengunjung</p>
    </div>
    <nav class="dash-nav">
        <div class="dash-nav-label">Menu</div>
        <a href="<?= BASE_URL ?>dashboard/pembeli/" class="<?= $currentPage=='dashboard'?'active':'' ?>"><i class="fas fa-home"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>dashboard/pembeli/pesanan-saya.php" class="<?= $currentPage=='pesanan'?'active':'' ?>"><i class="fas fa-receipt"></i> Pesanan Saya</a>
        <a href="<?= BASE_URL ?>dashboard/pembeli/voucher-saya.php" class="<?= $currentPage=='voucher'?'active':'' ?>"><i class="fas fa-ticket"></i> Voucher Saya</a>
        <a href="<?= BASE_URL ?>dashboard/pembeli/profil.php" class="<?= $currentPage=='profil'?'active':'' ?>"><i class="fas fa-user-edit"></i> Edit Profil</a>
        <div class="dash-nav-label">Lainnya</div>
        <a href="<?= BASE_URL ?>tiket/pesan.php"><i class="fas fa-shopping-cart"></i> Pesan Tiket</a>
        <a href="<?= BASE_URL ?>buku-tamu/tampil.php"><i class="fas fa-book"></i> Buku Tamu</a>
        <a href="<?= BASE_URL ?>"><i class="fas fa-globe"></i> Ke Website</a>
        <a href="#" id="toggleTheme" class="theme-toggle-link"><i class="fas fa-moon"></i> Ganti Tema</a>
        <a href="<?= BASE_URL ?>auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
</aside>
