    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h4>🦧 SOUL</h4>
                    <p class="footer-brand">Sanctuary konservasi orangutan di Indonesia. Kami berkomitmen untuk melindungi dan merehabilitasi orangutan serta habitat alaminya.</p>
                </div>
                <div>
                    <h4>Menu</h4>
                    <div class="footer-links">
                        <a href="<?= BASE_URL ?>">Home</a>
                        <a href="<?= BASE_URL ?>tiket/pesan.php">Pesan Tiket</a>
                        <a href="<?= BASE_URL ?>buku-tamu/tampil.php">Buku Tamu</a>
                        <a href="<?= BASE_URL ?>auth/login.php">Login</a>
                    </div>
                </div>
                <div>
                    <h4>Jam Operasional</h4>
                    <div class="footer-links">
                        <p>Senin - Jumat: 08:00 - 17:00</p>
                        <p>Sabtu - Minggu: 07:00 - 18:00</p>
                        <p>Hari Libur Nasional: 08:00 - 17:00</p>
                    </div>
                </div>
                <div>
                    <h4>Kontak</h4>
                    <div class="footer-links">
                        <p><i class="fas fa-map-marker-alt"></i> Jl. Hutan Tropis No. 88</p>
                        <p><i class="fas fa-phone"></i> (021) 1234-5678</p>
                        <p><i class="fas fa-envelope"></i> info@soulorangutan.com</p>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> Save Orangutan Unite for nature & wildlife. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
    <script src="<?= BASE_URL ?>script.js"></script>
    <?php if (isset($extraJs)): ?>
    <script src="<?= $extraJs ?>"></script>
    <?php endif; ?>
</body>
</html>
