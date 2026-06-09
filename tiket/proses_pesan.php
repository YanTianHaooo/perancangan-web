<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('tiket/pesan.php');
}

$tanggal = $_POST['tanggal_kunjungan'] ?? '';
$metode = $_POST['metode_bayar'] ?? '';
$tikets = $_POST['tiket'] ?? [];

// Validasi tanggal dan metode
if (empty($tanggal) || empty($metode)) {
    setFlash('error', 'Tanggal dan metode pembayaran wajib diisi.');
    redirect('tiket/pesan.php');
}

$tiketValid = [];

foreach ($tikets as $id => $qty) {
    $id = intval($id);
    $qty = intval($qty);

    if ($qty > 0) {
        $tiketValid[$id] = $qty;
    }
}

if (empty($tiketValid)) {
    setFlash('error', 'Pilih minimal 1 tiket.');
    redirect('tiket/pesan.php');
}

$totalHarga = 0;
$totalTiket = 0;
$details = [];

foreach ($tiketValid as $id => $qty) {
    $stmt = $conn->prepare("SELECT id, nama, harga FROM kategori_tiket WHERE id = ? AND status = 'aktif'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        if (stripos($row['nama'], 'rombongan') !== false && $qty < 20) {
            $stmt->close();
            setFlash('error', 'Tiket Rombongan minimal harus memesan 20 tiket.');
            redirect('tiket/pesan.php');
        }

        $subtotal = $row['harga'] * $qty;
        $totalHarga += $subtotal;
        $totalTiket += $qty;

        $details[] = [
            'kategori_id' => $row['id'],
            'nama' => $row['nama'],
            'harga' => $row['harga'],
            'jumlah' => $qty,
            'subtotal' => $subtotal
        ];
    }

    $stmt->close();
}

if (empty($details)) {
    setFlash('error', 'Tiket yang dipilih tidak valid.');
    redirect('tiket/pesan.php');
}

// Buat pesanan
$conn->begin_transaction();

try {
    $kodePesanan = generateKodePesanan($conn);
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("
        INSERT INTO pesanan 
        (user_id, kode_pesanan, tanggal_kunjungan, total_harga, status, metode_bayar) 
        VALUES (?, ?, ?, ?, 'dibayar', ?)
    ");
    $stmt->bind_param("issds", $userId, $kodePesanan, $tanggal, $totalHarga, $metode);
    $stmt->execute();

    $pesananId = $conn->insert_id;
    $stmt->close();

    foreach ($details as $d) {
        $stmt = $conn->prepare("
            INSERT INTO detail_pesanan 
            (pesanan_id, kategori_tiket_id, jumlah, subtotal) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("iiid", $pesananId, $d['kategori_id'], $d['jumlah'], $d['subtotal']);
        $stmt->execute();
        $stmt->close();
    }

    $kodeVoucher = generateVoucher($conn);

    $stmt = $conn->prepare("
        INSERT INTO voucher 
        (pesanan_id, kode_voucher, max_penggunaan, jumlah_digunakan, status) 
        VALUES (?, ?, ?, 0, 'aktif')
    ");
    $stmt->bind_param("isi", $pesananId, $kodeVoucher, $totalTiket);
    $stmt->execute();
    $stmt->close();

    $conn->commit();

    $_SESSION['pesanan_sukses'] = $pesananId;

    header("Location: " . BASE_URL . "tiket/konfirmasi.php?id=" . $pesananId);
    exit();

} catch (Exception $e) {
    $conn->rollback();
    setFlash('error', 'Gagal memproses pesanan: ' . $e->getMessage());
    redirect('tiket/pesan.php');
}