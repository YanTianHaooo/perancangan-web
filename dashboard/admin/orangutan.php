<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'tambah') {
        $gambar = 'sari.JPG';

        $stmt = $conn->prepare("
            INSERT INTO orangutan 
            (nama, jenis, deskripsi, gambar, tahun_lahir, tahun_penyelamatan, kondisi, jenis_kelamin)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssisss",
            $_POST['nama'],
            $_POST['jenis'],
            $_POST['deskripsi'],
            $gambar,
            $_POST['tahun_lahir'],
            $_POST['tahun_penyelamatan'],
            $_POST['kondisi'],
            $_POST['jenis_kelamin']
        );

        $stmt->execute();
        $stmt->close();

        setFlash('success', 'Data orangutan berhasil ditambahkan.');
    } elseif ($action === 'hapus') {
        $id = intval($_POST['id']);
        $conn->query("DELETE FROM orangutan WHERE id = $id");

        setFlash('success', 'Data orangutan berhasil dihapus.');
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$orangutans = $conn->query("
    SELECT 
        id, 
        nama, 
        jenis, 
        tahun_lahir, 
        tahun_penyelamatan, 
        jenis_kelamin, 
        kondisi, 
        deskripsi 
    FROM orangutan 
    ORDER BY id ASC
");

$currentPage = 'orangutan';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Orangutan | Admin</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>dashboard/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="dash-layout">
    <?php include 'sidebar.php'; ?>

    <main class="dash-main">
        <div class="dash-topbar">
            <h2>🦧 Data Orangutan</h2>

            <button 
                onclick="document.getElementById('modalTambah').classList.add('active')" 
                class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>

        <div class="dash-content">
            <?= getFlash() ?>

            <div class="dash-card">
                <div class="dash-card-body" style="padding:0;">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Tahun Penyelamatan</th>
                                    <th>Perkiraan Tahun Lahir</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Kondisi</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php while ($o = $orangutans->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= e($o['id']) ?></td>
                                        <td><strong><?= e($o['nama']) ?></strong></td>
                                        <td><?= e($o['jenis']) ?></td>
                                        <td><?= e($o['tahun_penyelamatan']) ?></td>
                                        <td><?= e($o['tahun_lahir']) ?></td>
                                        <td><?= e($o['jenis_kelamin']) ?></td>
                                        <td><?= e($o['kondisi']) ?></td>
                                        <td><?= e($o['deskripsi']) ?></td>
                                        <td>
                                            <form method="POST" style="display:inline;" onsubmit="return confirm('Hapus data ini?')">
                                                <input type="hidden" name="action" value="hapus">
                                                <input type="hidden" name="id" value="<?= e($o['id']) ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Hapus
                                                </button>
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
        <h3>Tambah Orangutan</h3>

        <form method="POST">
            <input type="hidden" name="action" value="tambah">

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Jenis</label>
                <input type="text" name="jenis" class="form-control" placeholder="Contoh: Orangutan Sumatera">
            </div>

            <div class="form-group">
                <label>Tahun Penyelamatan</label>
                <input type="date" name="tahun_penyelamatan" class="form-control">
            </div>

            <div class="form-group">
                <label>Perkiraan Tahun Lahir</label>
                <input type="number" name="tahun_lahir" class="form-control" min="1900" max="<?= date('Y') ?>">
            </div>

            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="Jantan">Jantan</option>
                    <option value="Betina">Betina</option>
                </select>
            </div>

            <div class="form-group">
                <label>Kondisi</label>
                <input type="text" name="kondisi" class="form-control" placeholder="Contoh: Buta, Lumpuh, Sehat">
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="deskripsi" class="form-control"></textarea>
            </div>

            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button 
                    type="button" 
                    onclick="this.closest('.modal-overlay').classList.remove('active')" 
                    class="btn btn-outline btn-sm">
                    Batal
                </button>

                <button type="submit" class="btn btn-primary btn-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script src="<?= BASE_URL ?>script.js"></script>

</body>
</html>