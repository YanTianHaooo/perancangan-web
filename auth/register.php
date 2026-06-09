<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (isLoggedIn()) { redirect('dashboard/'); }

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telepon = trim($_POST['telepon'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi_password'] ?? '';
    
    if (empty($nama) || empty($email) || empty($password)) {
        $error = 'Nama, email, dan password wajib diisi.';
    } elseif ($password !== $konfirmasi) {
        $error = 'Konfirmasi password tidak cocok.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = 'Email sudah terdaftar.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (nama, email, password, telepon, role) VALUES (?, ?, ?, ?, 'pengunjung')");
            $stmt->bind_param("ssss", $nama, $email, $hash, $telepon);
            if ($stmt->execute()) {
                setFlash('success', 'Pendaftaran berhasil! Silakan login.');
                redirect('auth/login.php');
            } else {
                $error = 'Gagal mendaftar. Coba lagi.';
            }
            $stmt->close();
        }
        $check->close();
    }
}

$pageTitle = 'Daftar';
$extraCss = BASE_URL . 'auth/auth.css';
require_once '../includes/header.php';
?>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>🦧</h1>
                <h2>Buat Akun Baru</h2>
                <p>Daftar untuk memesan tiket Orang Hutan Heaven</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error">❌ <?= e($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nama"><i class="fas fa-user"></i> Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama lengkap Anda" value="<?= e($nama ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="contoh@email.com" value="<?= e($email ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="telepon"><i class="fas fa-phone"></i> No. Telepon</label>
                    <input type="text" id="telepon" name="telepon" class="form-control" placeholder="08xxxxxxxxxx" value="<?= e($telepon ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                </div>
                <div class="form-group">
                    <label for="konfirmasi_password"><i class="fas fa-lock"></i> Konfirmasi Password</label>
                    <input type="password" id="konfirmasi_password" name="konfirmasi_password" class="form-control" placeholder="Ulangi password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Daftar</button>
            </form>
            
            <div class="auth-footer">
                <p>Sudah punya akun? <a href="<?= BASE_URL ?>auth/login.php">Masuk</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
