<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';
if (!isLoggedIn()) { redirect('auth/login.php'); }

$uid = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $telepon = trim($_POST['telepon'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    
    if (!empty($_POST['password_baru'])) {
        if (strlen($_POST['password_baru']) < 6) {
            setFlash('error', 'Password minimal 6 karakter.');
        } else {
            $hash = password_hash($_POST['password_baru'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET nama=?, telepon=?, alamat=?, password=? WHERE id=?");
            $stmt->bind_param("ssssi", $nama, $telepon, $alamat, $hash, $uid);
            $stmt->execute(); $stmt->close();
            $_SESSION['nama'] = $nama;
            setFlash('success', 'Profil dan password berhasil diupdate.');
        }
    } else {
        $stmt = $conn->prepare("UPDATE users SET nama=?, telepon=?, alamat=? WHERE id=?");
        $stmt->bind_param("sssi", $nama, $telepon, $alamat, $uid);
        $stmt->execute(); $stmt->close();
        $_SESSION['nama'] = $nama;
        setFlash('success', 'Profil berhasil diupdate.');
    }
    header("Location: " . $_SERVER['PHP_SELF']); exit();
}

$user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
$currentPage = 'profil';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Edit Profil | Orang Hutan Heaven</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>dashboard/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="dash-layout">
    <?php include 'sidebar.php'; ?>
    <main class="dash-main">
        <div class="dash-topbar"><h2>👤 Edit Profil</h2></div>
        <div class="dash-content">
            <?= getFlash() ?>
            <div class="dash-card" style="max-width:600px;">
                <div class="dash-card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= e($user['nama']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" value="<?= e($user['email']) ?>" disabled>
                            <small style="color:var(--gray-500);">Email tidak dapat diubah</small>
                        </div>
                        <div class="form-group">
                            <label>No. Telepon</label>
                            <input type="text" name="telepon" class="form-control" value="<?= e($user['telepon']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control"><?= e($user['alamat']) ?></textarea>
                        </div>
                        <hr style="margin:25px 0;border:none;border-top:1px solid var(--gray-200);">
                        <h4 style="margin-bottom:15px;">🔒 Ganti Password (opsional)</h4>
                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" name="password_baru" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>script.js"></script>
</body>
</html>
