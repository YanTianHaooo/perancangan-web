<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 9;
$offset = ($page - 1) * $perPage;

$totalResult = $conn->query("SELECT COUNT(*) as total FROM buku_tamu");
$total = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($total / $perPage);

$avgResult = $conn->query("SELECT AVG(rating) as avg_rating FROM buku_tamu");
$avgRating = round($avgResult->fetch_assoc()['avg_rating'], 1);

$entries = $conn->query("SELECT bt.*, v.kode_voucher FROM buku_tamu bt JOIN voucher v ON bt.voucher_id=v.id ORDER BY bt.created_at DESC LIMIT $perPage OFFSET $offset");

$pageTitle = 'Buku Tamu';
$extraCss = BASE_URL . 'buku-tamu/buku-tamu.css';
require_once '../includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>📖 Buku Tamu Pengunjung</h1>
        <p>Kesan dan pesan dari pengunjung Orang Hutan Heaven</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <!-- Stats -->
        <div class="grid-3" style="margin-bottom:40px;">
            <div class="card" style="padding:25px;text-align:center;">
                <div style="font-size:2rem;font-weight:800;color:var(--primary);"><?= $total ?></div>
                <div style="color:var(--gray-500);font-size:.85rem;">Total Pengunjung</div>
            </div>
            <div class="card" style="padding:25px;text-align:center;">
                <div style="font-size:2rem;font-weight:800;color:var(--secondary);"><?= $avgRating ?: '0' ?> ★</div>
                <div style="color:var(--gray-500);font-size:.85rem;">Rating Rata-rata</div>
            </div>
            <div class="card" style="padding:25px;text-align:center;">
                <a href="<?= BASE_URL ?>buku-tamu/" class="btn btn-primary" style="width:100%;justify-content:center;">✍️ Isi Buku Tamu</a>
            </div>
        </div>

        <?php if ($entries->num_rows > 0): ?>
        <div class="grid-3">
            <?php while($bt = $entries->fetch_assoc()): ?>
            <div class="testimonial-card card" style="padding:25px;">

            <h3 style="margin-bottom:10px;padding-left:8px;">
        <?= e($bt['nama_pengunjung']) ?>
            </h3>
            
                        <p style="margin:15px 0;padding:0 8px;font-style:italic;line-height:1.7;">
                    "<?= e($bt['pesan'] ?: 'Tidak ada pesan.') ?>"
                        </p>

            <div style="margin-bottom:15px;padding-left:8px;">
        <?= renderStars($bt['rating']) ?>
            </div>

            <div style="border-top:1px solid var(--gray-200);
                padding-top:12px;
                margin-top:12px;
                padding-left:8px;
                font-size:.8rem;
                color:var(--gray-500);">
        <?= timeAgo($bt['created_at']) ?>
            </div>

            </div>
            <?php endwhile; ?>
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page-1 ?>">← Prev</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $page): ?>
                    <span class="active"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page+1 ?>">Next →</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <div class="text-center" style="padding:60px 0;">
            <div style="font-size:4rem;margin-bottom:15px;">📭</div>
            <h3>Belum Ada Entri Buku Tamu</h3>
            <p style="color:var(--gray-500);margin-bottom:20px;">Jadilah yang pertama mengisi buku tamu!</p>
            <a href="<?= BASE_URL ?>buku-tamu/" class="btn btn-primary">✍️ Isi Buku Tamu</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
