<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

date_default_timezone_set('Asia/Jakarta');
$conn->query("SET time_zone = '+07:00'");

$error = '';
$success = false;
$sisaVoucher = null;
$totalJatahVoucher = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kodeVoucher = strtoupper(trim($_POST['kode_voucher'] ?? ''));
    $nama = trim($_POST['nama_pengunjung'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');
    $rating = intval($_POST['rating'] ?? 5);
    $jumlah = 1;

    if (empty($kodeVoucher) || empty($nama)) {
        $error = 'Kode voucher dan nama wajib diisi.';
    } else {
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("
                SELECT
                    v.id,
                    v.status,
                    v.max_penggunaan,
                    v.jumlah_digunakan,
                    p.id AS pesanan_id,
                    p.tanggal_kunjungan
                FROM voucher v
                JOIN pesanan p ON v.pesanan_id = p.id
                WHERE v.kode_voucher = ?
                FOR UPDATE
            ");
            $stmt->bind_param("s", $kodeVoucher);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                $error = 'Kode voucher tidak ditemukan. Pastikan kode yang Anda masukkan benar.';
            } else {
                $voucher = $result->fetch_assoc();
                $voucherId = (int)$voucher['id'];
                $pesananId = (int)$voucher['pesanan_id'];
                $totalJatahVoucher = max(1, (int)$voucher['max_penggunaan']);
                $jumlahDigunakan = max(0, (int)$voucher['jumlah_digunakan']);
                $sisaVoucher = $totalJatahVoucher - $jumlahDigunakan;

                if ($voucher['status'] === 'expired') {
                    $error = 'Kode voucher ini sudah expired.';
                } elseif ($jumlahDigunakan >= $totalJatahVoucher) {
                    $conn->query("UPDATE voucher SET status='digunakan', used_at=COALESCE(used_at, NOW()) WHERE id=$voucherId");
                    $conn->query("UPDATE pesanan SET status='digunakan' WHERE id=$pesananId");
                    $error = 'Jatah penggunaan kode voucher ini sudah habis.';
                } else {
                    $createdAt = date('Y-m-d H:i:s');

$stmt2 = $conn->prepare("
    INSERT INTO buku_tamu 
    (voucher_id, nama_pengunjung, pesan, rating, created_at) 
    VALUES (?, ?, ?, ?, ?)
");

$stmt2->bind_param("issis", $voucherId, $nama, $pesan, $rating, $createdAt);
                    $stmt2->execute();
                    $stmt2->close();

                    $jumlahDigunakanBaru = $jumlahDigunakan + 1;
                    $sisaVoucher = $totalJatahVoucher - $jumlahDigunakanBaru;

                    if ($jumlahDigunakanBaru >= $totalJatahVoucher) {
                        $conn->query("UPDATE voucher SET jumlah_digunakan=$jumlahDigunakanBaru, status='digunakan', used_at=NOW() WHERE id=$voucherId");
                        $conn->query("UPDATE pesanan SET status='digunakan' WHERE id=$pesananId");
                    } else {
                        $conn->query("UPDATE voucher SET jumlah_digunakan=$jumlahDigunakanBaru, status='aktif', used_at=NULL WHERE id=$voucherId");
                    }

                    $success = true;
                }
            }

            $stmt->close();
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            $error = 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Buku Tamu';
$extraCss = BASE_URL . 'buku-tamu/buku-tamu.css';
require_once '../includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>📖 Buku Tamu Pengunjung</h1>
        <p>Masukkan kode voucher Anda untuk mengisi buku tamu</p>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width:750px;">

        <?php if ($success): ?>
        <div class="card" style="padding:40px;text-align:center;">
            <div style="font-size:4rem;margin-bottom:15px;">🎉</div>
            <h2>Terima Kasih!</h2>
            <p style="color:var(--gray-600);margin:15px 0;">Buku tamu Anda telah berhasil disimpan. Selamat menikmati kunjungan di Orang Hutan Heaven!</p>
            <?php if ($sisaVoucher !== null): ?>
            <p style="color:var(--gray-500);font-size:.95rem;">Sisa penggunaan voucher: <b><?= $sisaVoucher ?></b> dari <?= $totalJatahVoucher ?> jatah.</p>
            <?php endif; ?>
            <div style="display:flex;gap:15px;justify-content:center;margin-top:20px;">
                <a href="<?= BASE_URL ?>buku-tamu/tampil.php" class="btn btn-primary">Lihat Buku Tamu</a>
                <a href="<?= BASE_URL ?>buku-tamu/" class="btn btn-outline"><i class="fas fa-book"></i> Isi Buku Tamu</a>
                <a href="<?= BASE_URL ?>" class="btn btn-outline">Kembali ke Home</a>
            </div>
        </div>
        <?php else: ?>

        <?php if ($error): ?>
            <div class="alert alert-error">❌ <?= e($error) ?></div>
        <?php endif; ?>
        <?= getFlash() ?>

        <div class="card" style="padding:35px;">
            <div style="text-align:center;margin-bottom:25px;">
                <h3>Validasi Kode Voucher</h3>
                <p style="color:var(--gray-500);font-size:.9rem;">Masukkan kode voucher yang Anda dapat saat memesan tiket</p>
            </div>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="kode_voucher"><i class="fas fa-ticket"></i> Kode Voucher</label>
                    <input type="text" id="kode_voucher" name="kode_voucher" class="form-control voucher-input" placeholder="Contoh: ABC123" maxlength="10" value="<?= e($kodeVoucher ?? '') ?>" required style="text-transform:uppercase;text-align:center;font-size:1.3rem;font-weight:700;letter-spacing:5px;">
                </div>

                <hr style="margin:25px 0;border:none;border-top:1px solid var(--gray-200);">

                <div class="form-group">
                    <label for="nama"><i class="fas fa-user"></i> Nama Pengunjung</label>
                    <input type="text" id="nama" name="nama_pengunjung" class="form-control" placeholder="Nama lengkap Anda" value="<?= e($nama ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-star"></i> Rating Kunjungan</label>
                    <div class="star-rating-input">
                        <input type="hidden" name="rating" value="5">
                        <span class="star active" data-value="1">★</span>
                        <span class="star active" data-value="2">★</span>
                        <span class="star active" data-value="3">★</span>
                        <span class="star active" data-value="4">★</span>
                        <span class="star active" data-value="5">★</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="pesan"><i class="fas fa-comment"></i> Pesan & Kesan</label>
                    <textarea id="pesan" name="pesan" class="form-control" placeholder="Ceritakan pengalaman Anda mengunjungi Orang Hutan Heaven..."><?= e($pesan ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;">
                    <i class="fas fa-paper-plane"></i> Kirim Buku Tamu
                </button>
            </form>
        </div>
        <?php endif; ?>

        <div class="text-center mt-30">
            <a href="<?= BASE_URL ?>buku-tamu/tampil.php" class="btn btn-outline btn-sm">📖 Lihat Semua Buku Tamu →</a>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
