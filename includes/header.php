<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Orang Hutan Heaven - Sanctuary konservasi orangutan terbaik di Indonesia">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' | ' : '' ?>Orang Hutan Heaven</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <?php if (isset($extraCss)): ?>
    <link rel="stylesheet" href="<?= $extraCss ?>">
    <?php endif; ?>
</head>
<body>
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-theme');
        }
    </script>
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="<?= BASE_URL ?>" class="nav-logo">

    <div class="logo-main">
        🦧 SOUL
    </div>

    <div class="logo-divider"></div>

    <span class="logo-desc">
        Save Orangutan<br> Unite for 
         
        nature & wildlife
    </span>

</a>
            <div class="nav-menu" id="navMenu">
                <a href="<?= BASE_URL ?>" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' && !strpos($_SERVER['PHP_SELF'],'dashboard') ? 'active' : '' ?>">Home</a>
                <a href="<?= BASE_URL ?>#orangutan">Orangutan</a>
                <a href="<?= BASE_URL ?>#fasilitas">Fasilitas</a>
                <a href="<?= BASE_URL ?>#harga">Harga</a>
                <a href="<?= BASE_URL ?>buku-tamu/tampil.php">Buku Tamu</a>
                <?php if (isLoggedIn()): ?>
                    <a href="<?= BASE_URL ?>tiket/pesan.php" class="btn-nav">🎫 Pesan Tiket</a>
                    <a href="<?= BASE_URL ?>dashboard/" class="profile-btn">
    <i class="fas fa-user-circle"></i>

    <?php if($_SESSION['role'] == 'admin'): ?>
        <span>Admin</span>
    <?php else: ?>
        <span>Profile</span>
    <?php endif; ?>
</a>
                    <a href="<?= BASE_URL ?>auth/logout.php">Logout</a>
                <?php else: ?>
                    <a href="#" id="toggleTheme" class="theme-toggle-link nav-theme-toggle"><i class="fas fa-moon"></i> Ganti Tema</a>
                    <a href="<?= BASE_URL ?>auth/login.php" class="btn-nav">Masuk</a>
                <?php endif; ?>
            </div>
            <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>
