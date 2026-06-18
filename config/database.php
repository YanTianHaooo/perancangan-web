<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'orangutan_heaven');

define('BASE_URL', '/Perancangan-Web    /');

define('SITE_NAME', 'Save Orangutan Unite for nature & wildlife');

date_default_timezone_set('Asia/Jakarta');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die('<div style="text-align:center;padding:50px;font-family:Arial;">
        <h2>❌ Koneksi Database Gagal</h2>
        <p>' . $conn->connect_error . '</p>
        <p>Pastikan MySQL XAMPP sudah aktif dan database <b>orangutan_heaven</b> sudah dibuat.</p>
        <p>Import file <b>zoo_db.sql</b> melalui phpMyAdmin.</p>
    </div>');
}

$conn->set_charset("utf8mb4");

$conn->query("SET time_zone = '+07:00'");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function ensureVoucherUsageColumns($conn) {
    $checkMax = $conn->query("SHOW COLUMNS FROM voucher LIKE 'max_penggunaan'");
    if ($checkMax && $checkMax->num_rows == 0) {
        $conn->query("ALTER TABLE voucher ADD COLUMN max_penggunaan INT NOT NULL DEFAULT 1 AFTER status");
    }

    $checkUsed = $conn->query("SHOW COLUMNS FROM voucher LIKE 'jumlah_digunakan'");
    if ($checkUsed && $checkUsed->num_rows == 0) {
        $conn->query("ALTER TABLE voucher ADD COLUMN jumlah_digunakan INT NOT NULL DEFAULT 0 AFTER max_penggunaan");
    }

    $conn->query("
        UPDATE voucher v
        JOIN (
            SELECT pesanan_id, GREATEST(1, COALESCE(SUM(jumlah), 1)) AS total_tiket
            FROM detail_pesanan
            GROUP BY pesanan_id
        ) d ON d.pesanan_id = v.pesanan_id
        SET v.max_penggunaan = d.total_tiket
        WHERE v.max_penggunaan IS NULL OR v.max_penggunaan < d.total_tiket
    ");

    $conn->query("
        UPDATE voucher v
        LEFT JOIN (
            SELECT voucher_id, COUNT(*) AS total_pakai
            FROM buku_tamu
            GROUP BY voucher_id
        ) bt ON bt.voucher_id = v.id
        SET v.jumlah_digunakan = COALESCE(bt.total_pakai, 0)
    ");
}

ensureVoucherUsageColumns($conn);
?>