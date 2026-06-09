<?php
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function generateVoucher($conn) {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    do {
        $kode = '';
        for ($i = 0; $i < 6; $i++) {
            $kode .= $chars[random_int(0, strlen($chars) - 1)];
        }
        $check = $conn->query("SELECT id FROM voucher WHERE kode_voucher = '$kode'");
    } while ($check->num_rows > 0);
    return $kode;
}

function generateKodePesanan($conn) {
    $prefix = 'OHH';
    $date = date('Ymd');
    do {
        $random = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $kode = $prefix . $date . $random;
        $check = $conn->query("SELECT id FROM pesanan WHERE kode_pesanan = '$kode'");
    } while ($check->num_rows > 0);
    return $kode;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $icon = $flash['type'] === 'success' ? '✅' : ($flash['type'] === 'error' ? '❌' : 'ℹ️');
        return '<div class="alert alert-' . $flash['type'] . '">' . $icon . ' ' . $flash['message'] . '</div>';
    }
    return '';
}

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function timeAgo($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->y > 0) return $diff->y . ' tahun lalu';
    if ($diff->m > 0) return $diff->m . ' bulan lalu';
    if ($diff->d > 0) return $diff->d . ' hari lalu';
    if ($diff->h > 0) return $diff->h . ' jam lalu';
    if ($diff->i > 0) return $diff->i . ' menit lalu';
    return 'Baru saja';
}

function renderStars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= $i <= $rating ? '★' : '☆';
    }
    return '<span class="stars">' . $stars . '</span>';
}

function getStats($conn) {
    $stats = [];
    
    $r = $conn->query("SELECT COUNT(*) as total FROM buku_tamu");
    $stats['total_pengunjung'] = $r->fetch_assoc()['total'];

    $r = $conn->query("SELECT COALESCE(SUM(total_harga),0) as total FROM pesanan WHERE status='dibayar' OR status='digunakan'");
    $stats['total_pendapatan'] = $r->fetch_assoc()['total'];
    
    $r = $conn->query("SELECT COALESCE(SUM(total_harga),0) as total FROM pesanan WHERE (status='dibayar' OR status='digunakan') AND DATE(created_at)=CURDATE()");
    $stats['pendapatan_hari_ini'] = $r->fetch_assoc()['total'];
    
    $r = $conn->query("SELECT COUNT(*) as total FROM pesanan");
    $stats['total_pesanan'] = $r->fetch_assoc()['total'];

    $r = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='pengunjung'");
    $stats['total_users'] = $r->fetch_assoc()['total'];

    $r = $conn->query("SELECT COUNT(*) as total FROM voucher WHERE status='aktif'");
    $stats['voucher_aktif'] = $r->fetch_assoc()['total'];
    
    return $stats;
}

function tanggalIndonesia($tanggal) {
    $bulan = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    $pecah = explode('-', date('Y-m-d', strtotime($tanggal)));

    return $pecah[2] . ' ' . $bulan[(int)$pecah[1]] . ' ' . $pecah[0];
}
