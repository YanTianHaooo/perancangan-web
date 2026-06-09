-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2026 at 04:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `orangutan_heaven`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku_tamu`
--

CREATE TABLE `buku_tamu` (
  `id` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  `nama_pengunjung` varchar(100) NOT NULL,
  `pesan` text DEFAULT NULL,
  `rating` int(11) DEFAULT 5,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id` int(11) NOT NULL,
  `pesanan_id` int(11) NOT NULL,
  `kategori_tiket_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fasilitas`
--

CREATE TABLE `fasilitas` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `ikon` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fasilitas`
--

INSERT INTO `fasilitas` (`id`, `nama`, `deskripsi`, `ikon`) VALUES
(1, 'Observation Deck', 'Platform observasi modern yang dibangun di area strategis sanctuary untuk memberikan pengalaman terbaik dalam melihat aktivitas orangutan secara langsung. Dari tempat ini, pengunjung dapat menikmati pemandangan hutan tropis sambil mengamati perilaku alami orangutan tanpa mengganggu habitat mereka. Area ini juga dilengkapi dengan pagar keamanan dan tempat duduk yang nyaman bagi pengunjung.', 'fa-binoculars'),
(2, 'Feeding Station', 'Feeding Station merupakan area khusus tempat para penjaga memberikan makanan kepada orangutan pada jadwal tertentu setiap harinya. Pengunjung dapat menyaksikan secara langsung proses pemberian makan sambil mempelajari jenis makanan sehat yang dikonsumsi orangutan. Fasilitas ini menjadi salah satu area favorit karena memberikan pengalaman edukatif sekaligus interaktif.', 'fa-apple-whole'),
(3, 'Education Center', 'Education Center adalah pusat pembelajaran yang menyediakan berbagai informasi mengenai konservasi orangutan, pelestarian hutan tropis, dan pentingnya menjaga keseimbangan ekosistem alam. Di area ini tersedia poster edukasi, dokumentasi penyelamatan orangutan, serta media interaktif yang membantu pengunjung memahami ancaman terhadap satwa liar Indonesia.', 'fa-graduation-cap'),
(4, 'Gift Shop', 'Gift Shop menyediakan berbagai suvenir menarik bertema orangutan dan konservasi alam, mulai dari gantungan kunci, pakaian, tas, hingga kerajinan tangan lokal. Seluruh produk dipilih untuk mendukung kampanye pelestarian lingkungan dan sebagian hasil penjualannya digunakan untuk membantu perawatan orangutan di sanctuary.', 'fa-bag-shopping'),
(5, 'Restoran', 'Restoran kami menandai awal dari area perhotelan berkelanjutan di Orangutan Haven. Dibangun dengan bambu dan material ramah lingkungan lainnya, restoran ini menawarkan ruang yang nyaman bagi pengunjung untuk bersantai dan menikmati alam setelah menjelajahi Haven.', 'fa-store'),
(6, 'Photo Spot', 'Photo Spot merupakan area foto dengan desain alami dan latar belakang hutan tropis yang menarik serta instagramable. Pengunjung dapat mengabadikan momen kunjungan bersama keluarga maupun teman dengan suasana yang nyaman dan estetik. Area ini dirancang khusus agar tetap menyatu dengan konsep alam sanctuary.', 'fa-camera'),
(7, 'Jembatan', 'Jembatan Haven merupakan konstruksi unik di Indonesia. Jembatan Haven adalah jembatan bambu bentang tunggal terpanjang di Asia Tenggara, dengan panjang 29,6 meter. Jembatan ini dibangun pada tahun 2017 oleh tim internal kami, dengan waktu konstruksi selama sembilan bulan.', 'fa-bridge'),
(8, 'Pulau Orangutan', 'Orangutan di Suaka Orangutan hidup di pulau-pulau besar yang ditumbuhi vegetasi alami dan dikelilingi oleh parit air. Masing-masing pulau memiliki area dalam ruangan yang terhubung sehingga hewan-hewan tersebut dapat berlindung dari hujan dan menemukan tempat yang kering dan hangat untuk tidur.\r\n\r\nTur berpemandu ke pulau-pulau orangutan berangkat secara teratur sesuai jadwal harian.', 'fa-umbrella-beach'),
(9, 'Pertanian Ramah Lingkungan', 'Eco-farm menanam buah dan sayuran organik menggunakan metode berkelanjutan. Sebagian besar hasil pertanian yang disajikan di restoran Orangutan Haven diproduksi di eco farm ini. Jangan ragu untuk berjalan-jalan dan menjelajahi area tersebut, dan jika Anda ingin membeli sesuatu, beri tahu staf kami.', 'fa-seedling'),
(10, 'Alam & Jalur Pendakian', 'Jelajahi Suaka Orangutan dan pelajari tentang keanekaragaman hayati tropis dengan berjalan kaki di beberapa jalur alam yang ada di lokasi. Dinamakan sesuai dengan beberapa lokasi penelitian SOCP di Sumatra, jalur alam ini menawarkan cara mudah untuk melihat dan mempelajari tentang hutan hujan tropis.', 'fa-person-hiking');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_tiket`
--

CREATE TABLE `kategori_tiket` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `kuota_harian` int(11) DEFAULT 100,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_tiket`
--

INSERT INTO `kategori_tiket` (`id`, `nama`, `harga`, `deskripsi`, `kuota_harian`, `status`, `created_at`) VALUES
(1, 'Tiket Dewasa', 75000.00, 'Tiket masuk untuk pengunjung dewasa (usia 12+). Akses ke semua area observasi orangutan.', 200, 'aktif', '2026-05-22 10:40:44'),
(2, 'Tiket Anak', 45000.00, 'Tiket masuk untuk anak-anak (usia 3-11). Termasuk akses playground & sesi edukasi.', 150, 'aktif', '2026-05-22 10:40:44'),
(3, 'Tiket VIP', 150000.00, 'Pengalaman premium! Tur privat dengan guide, feeding session, dan foto eksklusif.', 50, 'aktif', '2026-05-22 10:40:44'),
(4, 'Tiket Rombongan', 60000.00, 'Harga spesial untuk rombongan min. 20 orang. Termasuk guide & sesi edukasi.', 100, 'aktif', '2026-05-22 10:40:44');

-- --------------------------------------------------------

--
-- Table structure for table `orangutan`
--

CREATE TABLE `orangutan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tahun_lahir` year(4) DEFAULT NULL,
  `tahun_penyelamatan` date DEFAULT NULL,
  `kondisi` varchar(255) DEFAULT NULL,
  `jenis_kelamin` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orangutan`
--

INSERT INTO `orangutan` (`id`, `nama`, `jenis`, `deskripsi`, `gambar`, `tahun_lahir`, `tahun_penyelamatan`, `kondisi`, `jenis_kelamin`) VALUES
(1, 'KRISMON', 'Orangutan Sumatera', 'Krismon disita pada tahun 2016 sebagai hewan peliharaan ilegal. Ia telah dipelihara selama 19 tahun di dalam kandang logam yang ukurannya hanya sedikit lebih besar dari tubuhnya . Kita tahu ia menghabiskan 19 tahun di sana karena namanya merupakan singkatan dari \'Krisis Moneter\', atau Krisis Moneter, yang terjadi pada tahun 1997 di Indonesia. Setelah sekian lama, pintu yang ia gunakan untuk masuk ke kandang sudah tidak cukup besar lagi, sehingga kandang harus dibongkar untuk mengeluarkannya.\r\n\r\nSetelah menghabiskan bertahun-tahun di ruang yang sangat terbatas, Krismon masih menunjukkan kelemahan fisik dan mental yang cukup parah dan tidak akan pernah bisa bertahan hidup di alam liar .Sejak disita, Krismon telah menghabiskan banyak waktu berlatih dengan para penjaganya, mendapatkan hadiah karena bergerak ketika diminta. Ini berarti dia mendapatkan latihan fisik, semacam fisioterapi, dan hasilnya dia telah meningkat pesat.\r\n\r\nDia sekarang secara bertahap mulai terbiasa berada di luar ruangan di Suaka Orangutan dan memanjat tali serta menara, terlihat semakin percaya diri setiap harinya.', 'krismon.webp', '1996', '2016-05-30', 'Trauma dan kelemahan fisik', 'Laki-laki'),
(2, 'LEUSER', 'Orangutan Sumatera', 'Leuser disita sebagai hewan peliharaan ilegal ketika ia berusia sekitar 5 tahun dan dilepaskan ke alam liar di Taman Nasional Bukit Tigapuluh di Jambi setahun kemudian.\r\n\r\nIa hidup subur sebagai orangutan liar di hutan selama beberapa tahun, hingga suatu hari, ia memasuki lahan pertanian di dekatnya dan ditembak 62 kali oleh beberapa penduduk desa. Akibatnya, ia menjadi buta total dan tidak lagi dapat bernavigasi serta mencari makanan di hutan, sehingga ia kembali ke SOCP untuk perawatan jangka panjangLeuser adalah orangutan pertama yang diizinkan keluar ke salah satu pulau di Orangutan Haven . Ia sangat senang bisa keluar dari kandang sehingga tidak kembali ke rumahnya selama 6 minggu, tetapi sekarang ia dengan mudah datang untuk makan ketika para penjaganya memanggilnya.\r\n\r\nLeuser kini menikmati kesempatan ketiga untuk hidup damai , memanjat tali dan kayu di pulaunya serta menjelajahi lingkungan barunya.', 'leuser.jpg', '1999', '2004-02-20', 'Buta', 'Laki Laki'),
(3, 'FAHZREN', 'Orangutan Sumatera', 'Fahzren diselundupkan secara ilegal ke Malaysia saat masih bayi, dan tumbuh besar dengan tampil di \'pertunjukan hewan\', jenis pertunjukan yang melibatkan mengendarai sepeda, bermain golf, dan hal-hal semacam itu. Peraturan perdagangan hewan internasional mengharuskan hewan yang diselundupkan secara ilegal ke luar negeri untuk dipulangkan ke negara asalnya, sehingga ia dikembalikan ke Indonesia pada tahun 2013.Karena saat itu ia sudah menjadi orangutan jantan dewasa yang besar dan kuat, serta terbiasa berinteraksi dekat dengan manusia, diputuskan bahwa ia kemungkinan besar tidak akan mampu belajar bertahan hidup lagi di alam liar, dan berpotensi berbahaya jika bertemu dengan orang-orang di hutan. Oleh karena itu, diputuskan bahwa ia harus menghabiskan sisa hidupnya di Suaka Orangutan.\r\n\r\nKarena pengalamannya di Malaysia, Fahzren sangat tertarik pada hal-hal teknis dan telah merusak beberapa vegetasi dan bangunan di pulaunya. Dia juga suka pamer untuk memastikan orang-orang tahu bahwa dialah \'bosnya\' , tetapi sekarang dia jauh lebih santai dan tenang daripada sebelum dia pindah ke Suaka Orangutan.', 'fahzreen.webp', '1998', '2013-10-09', 'Sudah dewasa dan terlalu terbiasa dengan orang lain', 'Laki Laki'),
(4, 'DINA', 'Orangutan Sumatera', 'Dina diselamatkan dari perdagangan satwa liar ilegal ketika dia masih bayi, berusia kurang dari 1 tahun.\r\n\r\nKetika pertama kali tiba di SOCP setelah disita, ia didiagnosis menderita malaria, yang mengakibatkan infeksi otak yang melumpuhkannya hampir sepenuhnya dari leher ke bawah.Untungnya, setelah berbulan-bulan perawatan dan perhatian intensif, ia mendapatkan kembali 100% mobilitasnya , dan tidak lagi menunjukkan tanda-tanda fisik penyakitnya, tetapi ia tidak pernah mendapatkan kembali penglihatannya dan tetap hampir sepenuhnya buta.\r\n\r\nSelama beberapa tahun di SOCP, para penjaga bekerja sama dengan Dina untuk meningkatkan keterampilannya dan sekarang dia memiliki petualangan baru dalam hidupnya di Suaka Orangutan. Di hari-hari panas, dia suka bergelantungan di jembatannya dan bermain air untuk mendinginkan diri.', 'dina.webp', '2015', '2016-07-27', 'Buta', 'Perempuan'),
(5, 'LEWIS', 'Orangutan Sumatera', 'Meskipun mengalami kesulitan yang tak terbayangkan, Lewis telah berhasil mengatasi rintangan. Setelah pulih dari trauma akibat ditembak 40 kali oleh beberapa petani di tepi hutan, ia awalnya menemukan perawatan dan ketenangan di Pusat Karantina dan Rehabilitasi SOCP.Lewis adalah orangutan yang luar biasa tinggi, dengan lengan yang sangat panjang. Terkadang dia juga berjalan tegak lurus, dengan dua kaki, sangat mirip manusia!\r\n\r\nKini, Lewis menikmati babak baru dalam hidupnya di Orangutan Haven. Momen pertama kali ia keluar dari rumah dan menuju pulaunya sungguh menakjubkan. Dengan santai dan teratur, ia menjelajahi lingkungan barunya dengan penuh rasa ingin tahu, melambaikan tangannya ke sana kemari untuk \'merasakan\' apakah ada sesuatu di dekatnya, menunjukkan ketahanan makhluk-makhluk luar biasa ini.', 'lewis.jpg', '1991', '2016-08-30', 'Buta', 'Laki Laki'),
(6, 'DEK NONG', 'Orangutan Sumatera', 'Awalnya diselamatkan pada tahun 2007 sebagai hewan peliharaan ilegal dan dilepaskan ke alam liar setahun kemudian, Dek Nong menghadapi berbagai tantangan di hutan, termasuk kelumpuhan misterius pada tahun 2009.Meskipun ia pulih dengan sangat baik, pergelangan tangan kirinya tetap bengkok pada sudut yang tidak wajar, dan ia masih memiliki beberapa masalah kecil dengan beberapa persendian lainnya, tetapi selain itu ia sehat dan bugar.\r\n\r\nDek Nong adalah individu yang sangat cerdas dan ingin tahu , selalu mengamati siapa yang melakukan apa di sekitar lembah pulau itu. Ketika pertama kali diizinkan keluar ke salah satu pulau, dia dengan hati-hati menjelajahi jembatan, menara, dan keranjang, jelas menikmati kebebasan barunya.', 'dek nong.webp', '1999', '2007-09-07', 'Masalah artritis', 'Perempuan');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kode_pesanan` varchar(20) NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `status` enum('pending','dibayar','digunakan','expired','batal') DEFAULT 'pending',
  `metode_bayar` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pengunjung') DEFAULT 'pengunjung',
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

CREATE TABLE `voucher` (
  `id` int(11) NOT NULL,
  `pesanan_id` int(11) NOT NULL,
  `kode_voucher` varchar(10) NOT NULL,
  `max_penggunaan` int(11) NOT NULL DEFAULT 1,
  `jumlah_digunakan` int(11) NOT NULL DEFAULT 0,
  `status` enum('aktif','digunakan','expired') DEFAULT 'aktif',
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku_tamu`
--
ALTER TABLE `buku_tamu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voucher_id` (`voucher_id`);

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesanan_id` (`pesanan_id`),
  ADD KEY `kategori_tiket_id` (`kategori_tiket_id`);

--
-- Indexes for table `fasilitas`
--
ALTER TABLE `fasilitas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori_tiket`
--
ALTER TABLE `kategori_tiket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orangutan`
--
ALTER TABLE `orangutan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pesanan` (`kode_pesanan`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_voucher` (`kode_voucher`),
  ADD KEY `pesanan_id` (`pesanan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku_tamu`
--
ALTER TABLE `buku_tamu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `fasilitas`
--
ALTER TABLE `fasilitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `kategori_tiket`
--
ALTER TABLE `kategori_tiket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orangutan`
--
ALTER TABLE `orangutan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `voucher`
--
ALTER TABLE `voucher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buku_tamu`
--
ALTER TABLE `buku_tamu`
  ADD CONSTRAINT `buku_tamu_ibfk_1` FOREIGN KEY (`voucher_id`) REFERENCES `voucher` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`kategori_tiket_id`) REFERENCES `kategori_tiket` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `voucher`
--
ALTER TABLE `voucher`
  ADD CONSTRAINT `voucher_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
