<?php
$pageTitle = 'Home';
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$orangutans = $conn->query("SELECT id, nama, jenis, gambar, tahun_lahir, tahun_penyelamatan, jenis_kelamin, kondisi, deskripsi FROM orangutan ORDER BY id DESC");
if (!$orangutans) {
    die("Query orangutan gagal: " . $conn->error);
}

$fasilitas = $conn->query("SELECT * FROM fasilitas");
$tikets = $conn->query("SELECT * FROM kategori_tiket WHERE status='aktif'");
$bukuTamu = $conn->query("SELECT bt.*, v.kode_voucher FROM buku_tamu bt JOIN voucher v ON bt.voucher_id=v.id ORDER BY bt.created_at DESC LIMIT 3");
?>

<section class="hero" id="home">
    <div class="hero-content">
        <div class="hero-badge">🌿 Sanctuary Konservasi Orangutan</div>
        <h1>Selamat Datang di<br><span>SOUL</span></h1>
        <p>Temukan keajaiban orangutan di habitat alaminya. Dukung konservasi dan ciptakan kenangan tak terlupakan bersama primata paling cerdas di dunia.</p>
        <div class="hero-buttons">
            <a href="<?= BASE_URL ?>tiket/pesan.php" class="btn btn-primary btn-lg">🎫 Pesan Tiket Sekarang</a>
            <a href="#orangutan" class="btn btn-secondary btn-lg">Jelajahi →</a>
        </div>
    </div>
</section>

<section class="section" id="tentang">
    <div class="container">
        <div class="about-grid animate-on-scroll">
            <div class="about-img">
        <img id="aboutSlider" src="assets/images/lewis.jpg" alt="Orangutan">
        </div>
            <div>
                <span class="section-badge">Tentang Kami</span>
                <h2>Melindungi Orangutan,<br>Menjaga Masa Depan</h2>
                <p style="color:var(--gray-600);margin:20px 0;line-height:1.8;">
                    SOUL adalah sanctuary konservasi yang didedikasikan untuk perlindungan dan rehabilitasi orangutan Indonesia. Kami menyediakan lingkungan yang aman dan alami bagi orangutan yang diselamatkan dari perdagangan ilegal, deforestasi, dan konflik manusia-satwa.
                </p>
                <p style="color:var(--gray-600);margin-bottom:20px;line-height:1.8;">
                    Dengan mengunjungi kami, Anda turut berkontribusi langsung dalam upaya konservasi orangutan dan pelestarian hutan tropis Indonesia.
                </p>
                <div class="about-stats">
                    <div class="stat-item">
                        <div class="stat-number counter" data-target="50">0</div>
                        <div class="stat-label">Orangutan</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number counter" data-target="15">0</div>
                        <div class="stat-label">Tahun Beroperasi</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number counter" data-target="10000">0</div>
                        <div class="stat-label">Pengunjung/Tahun</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="orangutan" style="background:var(--white);">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <span class="section-badge">Penghuni Kami</span>
            <h2>Kenali Orangutan Kami</h2>
            <p>Setiap orangutan memiliki cerita unik. Kenali mereka lebih dekat dan jatuh cinta dengan kepribadian mereka.</p>
        </div>
        <div class="grid-3">
            <?php while($o = $orangutans->fetch_assoc()): ?>
            <div class="card orangutan-card animate-on-scroll"
                 data-nama="<?= e($o['nama']) ?>"
                 data-jenis="<?= e($o['jenis']) ?>"
                 data-gambar="<?= BASE_URL ?>assets/images/<?= e($o['gambar']) ?>"
                 data-tahun-penyelamatan="<?= tanggalIndonesia($o['tahun_penyelamatan']) ?>"
                 data-tahun-lahir="<?= e($o['tahun_lahir']) ?>"
                 data-jenis-kelamin="<?= e($o['jenis_kelamin']) ?>"
                 data-kondisi="<?= e($o['kondisi']) ?>">
                <img src="<?= BASE_URL ?>assets/images/<?= e($o['gambar']) ?>"
                     alt="<?= e($o['nama']) ?>"
                     class="card-img">
                <div class="card-body">
                    <span class="card-badge"><?= e($o['jenis']) ?></span>
                    <h3 class="card-title"><?= e($o['nama']) ?></h3>

                    <p><b>Tahun Penyelamatan:</b> <?= tanggalIndonesia($o['tahun_penyelamatan']) ?></p>
                    <p><b>Perkiraan Tahun Lahir:</b> <?= e($o['tahun_lahir']) ?></p>
                    <p><b>Jenis Kelamin:</b> <?= e($o['jenis_kelamin']) ?></p>
                    <p><b>Kondisi:</b> <?= e($o['kondisi']) ?></p>

                    <p>
                    <b>Keterangan :</b>
                    <?= e(substr($o['deskripsi'], 0, 100)) ?>
                    <?= strlen($o['deskripsi']) > 100 ? '...' : '' ?>
                    </p>

                    <div class="orangutan-full-desc" hidden><?= nl2br(e($o['deskripsi'])) ?></div>

                    <button class="btn btn-primary btn-keterangan"
                    onclick="openModal(`<?= nl2br(e($o['deskripsi'])) ?>`)">
                        Lihat Keterangan Lengkap
                    </button>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <div class="orangutan-modal" id="orangutanModal">
            <div class="orangutan-modal-box">
                <button type="button" class="orangutan-modal-close" id="closeOrangutanModal">&times;</button>
                <img src="" alt="Orangutan" class="orangutan-modal-img" id="modalGambar">

                <span class="card-badge" id="modalJenis"></span>
                <h2 id="modalNama"></h2>

                <div class="orangutan-detail-grid">
                    <p><b>Tahun Penyelamatan:</b> <span id="modalTahunPenyelamatan"></span></p>
                    <p><b>Perkiraan Tahun Lahir:</b> <span id="modalTahunLahir"></span></p>
                    <p><b>Jenis Kelamin:</b> <span id="modalJenisKelamin"></span></p>
                    <p><b>Kondisi:</b> <span id="modalKondisi"></span></p>
                </div>

                <h4>Keterangan Lengkap</h4>
                <p class="orangutan-modal-desc" id="modalDeskripsi"></p>
            </div>
        </div>
    </div>
</section>

<section class="section" id="fasilitas">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <span class="section-badge">Fasilitas</span>
            <h2>Fasilitas Lengkap</h2>
            <p>Nikmati berbagai fasilitas yang kami sediakan untuk pengalaman kunjungan terbaik Anda.</p>
        </div>
        <div class="grid-3">
            <?php while($f = $fasilitas->fetch_assoc()): ?>
            <div class="facility-card animate-on-scroll">
                <div class="facility-icon"><i class="fas <?= e($f['ikon']) ?>"></i></div>
                <h4><?= e($f['nama']) ?></h4>
                <p><?= e($f['deskripsi']) ?></p>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<section class="section" id="harga" style="background:var(--white);">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <span class="section-badge">Tiket</span>
            <h2>Harga Tiket Masuk</h2>
            <p>Pilih paket kunjungan yang sesuai dengan kebutuhan Anda.</p>
        </div>
        <div class="grid-4">
            <?php $i=0; while($t = $tikets->fetch_assoc()): $i++; ?>
            <div class="pricing-card <?= $i==3?'popular':'' ?> animate-on-scroll">
                <h3><?= e($t['nama']) ?></h3>
                <div class="price"><?= formatRupiah($t['harga']) ?><small>/orang</small></div>
                <p style="color:var(--gray-600);font-size:.9rem;"><?= e($t['deskripsi']) ?></p>
                <a href="<?= BASE_URL ?>tiket/pesan.php" class="btn btn-primary" style="margin-top:20px;width:100%;justify-content:center;">Pesan Sekarang</a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<section class="section" id="testimoni">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <span class="section-badge">Buku Tamu</span>
            <h2>Kata Pengunjung</h2>
            <p>Dengarkan pengalaman dari pengunjung yang telah datang ke sanctuary kami.</p>
        </div>
        <div class="grid-3">
            <?php while($bt = $bukuTamu->fetch_assoc()): ?>
            <div class="testimonial-card animate-on-scroll">
                <?= renderStars($bt['rating']) ?>
                <p>"<?= e($bt['pesan']) ?>"</p>
                <div class="author"><?= e($bt['nama_pengunjung']) ?> · <?= timeAgo($bt['created_at']) ?></div>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="text-center mt-30">
            <a href="<?= BASE_URL ?>buku-tamu/tampil.php" class="btn btn-outline">Lihat Semua Buku Tamu →</a>
        </div>
    </div>
</section>

<section style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:80px 0;text-align:center;color:var(--white);">
    <div class="container">
        <h2 style="color:var(--white);font-size:2.5rem;margin-bottom:15px;">Siap Bertemu Orangutan?</h2>
        <p style="color:rgba(255,255,255,.7);font-size:1.1rem;margin-bottom:30px;max-width:500px;margin-left:auto;margin-right:auto;">Pesan tiket sekarang dan dapatkan kode voucher untuk pengalaman tak terlupakan.</p>
        <a href="<?= BASE_URL ?>tiket/pesan.php" class="btn btn-primary btn-lg">🎫 Pesan Tiket Sekarang</a>
    </div>
</section>

<script>
const aboutImages = [
    'assets/images/lewis.jpg',
    'assets/images/dek nong.webp',
    'assets/images/dina.webp',
    'assets/images/fahzreen.webp',
    'assets/images/krismon.webp',
    'assets/images/leuser.jpg'
];


let currentImage = 0;

const slider = document.getElementById('aboutSlider');
const aboutBox = document.querySelector('.about-img');

setInterval(() => {

    aboutBox.classList.add('fade');
    slider.style.opacity = '0';

    setTimeout(() => {

        currentImage = (currentImage + 1) % aboutImages.length;

        slider.src = aboutImages[currentImage];

        setTimeout(() => {

            // Fade masuk
            slider.style.opacity = '1';
            aboutBox.classList.remove('fade');

        }, 100);

    }, 1200);

}, 4500);
</script>

<?php require_once 'includes/footer.php'; ?>
