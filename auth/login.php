<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (isLoggedIn()) { redirect('dashboard/'); }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi.';
    } else {
        $stmt = $conn->prepare("SELECT id, nama, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            $loginBerhasil = false;

            // Jika password di database sudah berbentuk hash
            if (password_verify($password, $user['password'])) {
                $loginBerhasil = true;
            }

            // Jika password di database masih mentah/plain text
            elseif ($password === $user['password']) {
                $hashBaru = password_hash($password, PASSWORD_DEFAULT);

                $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update->bind_param("si", $hashBaru, $user['id']);
                $update->execute();
                $update->close();

                $loginBerhasil = true;
            }

            if ($loginBerhasil) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                setFlash('success', 'Selamat datang, ' . $user['nama'] . '!');
                redirect('dashboard/');
            } else {
                $error = 'Password salah.';
            }
        } else {
            $error = 'Email tidak ditemukan.';
        }
        $stmt->close();
    }
}

$pageTitle = 'Login';
$extraCss = BASE_URL . 'auth/auth.css';
require_once '../includes/header.php';
?>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>🦧</h1>
                <h2>Masuk ke Akun</h2>
                <p>Selamat datang kembali di Orang Hutan Heaven</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error">❌ <?= e($error) ?></div>
            <?php endif; ?>
            <?= getFlash() ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="contoh@email.com" value="<?= e($email ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Masuk</button>
            </form>
            
            <div class="auth-footer">
                <p>Belum punya akun? <a href="<?= BASE_URL ?>auth/register.php">Daftar Sekarang</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>