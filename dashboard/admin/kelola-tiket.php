<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';
if (!isLoggedIn() || !isAdmin()) { redirect('auth/login.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'tambah') {
        $stmt = $conn->prepare("INSERT INTO kategori_tiket (nama,harga,deskripsi,kuota_harian,status) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sdsss", $_POST['nama'], $_POST['harga'], $_POST['deskripsi'], $_POST['kuota'], $_POST['status']);
        $stmt->execute(); $stmt->close();
        setFlash('success','Kategori tiket berhasil ditambahkan.');
    } elseif ($action === 'hapus') {
        $conn->query("DELETE FROM kategori_tiket WHERE id=" . intval($_POST['id']));
        setFlash('success','Kategori tiket berhasil dihapus.');
    }
    header("Location: " . $_SERVER['PHP_SELF']); exit();
}

$tikets = $conn->query("SELECT * FROM kategori_tiket ORDER BY id ASC");
$currentPage = 'tiket';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Kelola Tiket | Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>dashboard/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="dash-layout">
    <?php include 'sidebar.php'; ?>
    <main class="dash-main">
        <div class="dash-topbar">
            <h2>🎫 Kelola Tiket</h2>
            <button onclick="document.getElementById('modalTambah').classList.add('active')" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</button>
        </div>
        <div class="dash-content">
            <?= getFlash() ?>
            <div class="dash-card">
                <div class="dash-card-body" style="padding:0;">
                    <div class="table-responsive">
                    <table>
                        <thead><tr><th>ID</th><th>Nama</th><th>Harga</th><th>Kuota</th><th>Status</th><th>Aksi</th></tr></thead>
                        <tbody>
                        <?php while($t = $tikets->fetch_assoc()): ?>
                        <tr>
                            <td><?= $t['id'] ?></td>
                            <td><strong><?= e($t['nama']) ?></strong><br><small style="color:var(--gray-500);"><?= e(substr($t['deskripsi'],0,50)) ?></small></td>
                            <td><?= formatRupiah($t['harga']) ?></td>
                            <td><?= $t['kuota_harian'] ?></td>
                            <td><span class="badge badge-<?= $t['status']=='aktif'?'success':'danger' ?>"><?= $t['status'] ?></span></td>
                            <td>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Hapus?')">
                                    <input type="hidden" name="action" value="hapus"><input type="hidden" name="id" value="<?= $t['id'] ?>">
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<div class="modal-overlay" id="modalTambah">
    <div class="modal">
        <h3>Tambah Kategori Tiket</h3>
        <form method="POST">
            <input type="hidden" name="action" value="tambah">
            <div class="form-group"><label>Nama</label><input type="text" name="nama" class="form-control" required></div>
            <div class="form-group"><label>Harga (Rp)</label><input type="number" name="harga" class="form-control" required></div>
            <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"></textarea></div>
            <div class="form-group"><label>Kuota Harian</label><input type="number" name="kuota" class="form-control" value="100"></div>
            <div class="form-group"><label>Status</label><select name="status" class="form-control"><option value="aktif">Aktif</option><option value="nonaktif">Nonaktif</option></select></div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="this.closest('.modal-overlay').classList.remove('active')" class="btn btn-outline btn-sm">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script src="<?= BASE_URL ?>script.js"></script>
</body>
</html>
