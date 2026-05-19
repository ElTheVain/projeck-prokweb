-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Bulan Mei 2026 pada 15.06
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_donasi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `donasi`
--

CREATE TABLE `donasi` (
  `id_donasi` int(11) NOT NULL,
  `id_kampanye` int(11) NOT NULL,
  `id_donatur` int(11) NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `pesan_dukungan` text DEFAULT NULL,
  `bukti_transfer` varchar(255) NOT NULL,
  `status` enum('PENDING','VERIFIED','DITOLAK') NOT NULL DEFAULT 'PENDING',
  `tanggal_donasi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `donasi`
--

INSERT INTO `donasi` (`id_donasi`, `id_kampanye`, `id_donatur`, `nominal`, `metode_pembayaran`, `pesan_dukungan`, `bukti_transfer`, `status`, `tanggal_donasi`) VALUES
(1, 4, 4, 500000.00, 'BCA', 'semoga ini dapat membantu', 'bukti_1778838614_dog-hero.jpg', 'PENDING', '2026-05-15 09:50:14'),
(2, 2, 4, 500000.00, 'BCA', 'hay', 'bukti_1778838661_dog-hero.jpg', 'PENDING', '2026-05-15 09:51:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kampanye`
--

CREATE TABLE `kampanye` (
  `id_kampanye` int(11) NOT NULL,
  `id_penyelenggara` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `kategori` enum('Bencana','Pendidikan','Kesehatan','Lingkungan','Fasilitas Umum') NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `target_dana` decimal(15,2) NOT NULL,
  `dana_terkumpul` decimal(15,2) NOT NULL DEFAULT 0.00,
  `dana_pending` decimal(15,2) NOT NULL DEFAULT 0.00,
  `batas_waktu` date NOT NULL,
  `rekening_informasi` text NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kampanye`
--

INSERT INTO `kampanye` (`id_kampanye`, `id_penyelenggara`, `judul`, `kategori`, `lokasi`, `deskripsi`, `target_dana`, `dana_terkumpul`, `dana_pending`, `batas_waktu`, `rekening_informasi`, `gambar`, `created_at`) VALUES
(1, 2, 'Bantuan Sembako Banjir Bandang', 'Bencana', 'Demak', 'Bantuan darurat logistik pangan untuk korban banjir.', 50000000.00, 15000000.00, 0.00, '2026-08-01', 'BRI 0021-01-234567-53-1 a.n Yayasan Tanggap Darurat', 'bantuansembakobanjir.jpg', '2026-05-15 08:12:03'),
(2, 3, 'Renovasi Jembatan Rusak Desa Sukamaju', 'Fasilitas Umum', 'Bogor', 'Pembangunan kembali akses jembatan sekolah yang putus.', 75000000.00, 0.00, 500000.00, '2026-09-15', 'Mandiri 131-00-1234567-8 a.n Gerakan Membangun Desa', 'renovasi jembatan.jpeg', '2026-05-15 08:12:03'),
(4, 2, 'Sumur Bor Desa Kekeringan', 'Fasilitas Umum', 'Gunung Kidul, DIY', 'Krisis air bersih melanda warga setiap musim kemarau panjang. Donasi Anda akan digunakan untuk pengeboran sumur sedalam 100 meter dan instalasi pipa menuju tandon pusat desa agar warga tidak perlu lagi membeli air tangki yang mahal.', 180000000.00, 165000000.00, 500000.00, '2026-12-30', 'Mandiri 131-00-998877-6 a.n Sedekah Air Indonesia', 'sumur bor.jpeg', '2026-05-15 08:41:19'),
(5, 1, 'Patungan Gawai & Kuota Belajar Anak Yatim', 'Pendidikan', 'Yogyakarta', 'Banyak anak yatim di pinggiran kota terancam putus sekolah karena tidak memiliki fasilitas belajar digital yang memadai. Donasi yang terkumpul akan disalurkan dalam bentuk paket gawai belajar (tablet murah) dan subsidi paket internet selama 6 bulan untuk menunjang aktivitas sekolah mereka.', 35000000.00, 12000000.00, 0.00, '2026-07-20', 'BNI 0833-2112-99 a.n Komunitas Pintar Nusantara', 'pendidikanytm.jpg', '2026-05-15 10:45:21'),
(6, 3, 'Beasiswa Asa Juara Anak Pelosok Mentawai', 'Pendidikan', 'Mentawai', 'Anak-anak di pelosok kepulauan Mentawai harus berjalan kaki sejauh 5 KM untuk mencapai sekolah terdekat tanpa perlengkapan memadai. Melalui program crowdfunding ini, kami mengajak Anda membantu biaya SPP, seragam, sepatu, serta buku panduan belajar untuk 50 anak kurang mampu agar tetap bisa menggapai cita-cita mereka.', 60000000.00, 5000000.00, 0.00, '2026-08-15', 'Mandiri 137-00-112233-4 a.n Yayasan Indonesia Mengajar', 'beasiswamentawai.webp', '2026-05-15 10:45:21'),
(7, 4, 'Adopsi Bibit Mangrove Cegah Abrasi Pantai Demak', 'Lingkungan', 'Demak', 'Garis pantai di wilayah Sayung, Demak terus terkikis oleh hantaman abrasi air laut yang menenggelamkan rumah warga setempat. Melalui gerakan ini, setiap donasi Rp15.000 dari Anda akan dikonversi menjadi 1 bibit pohon mangrove yang ditanam dan dirawat bersama komunitas nelayan lokal untuk menjaga benteng pesisir pantai.', 40000000.00, 18500000.00, 0.00, '2026-06-30', 'Mandiri 131-00-554433-2 a.n Koalisi Hijau Nusantara', 'mangrove.png', '2026-05-15 10:46:49'),
(8, 2, 'Aksi Bersih Sampah Sungai & Edukasi Warga', 'Lingkungan', 'Bandung', 'Sungai Citarum masih terus tersumbat oleh tumpukan sampah plastik rumah tangga yang memicu banjir bandang tahunan ketika musim hujan tiba. Donasi Anda akan dialokasikan untuk membiayai upah harian relawan kebersihan, pengadaan jaring sampah waduk, alat pelindung diri, serta papan sosialisasi larangan membuang sampah di bantaran sungai.', 25000000.00, 3000000.00, 0.00, '2026-07-05', 'BCA 282-0112-901 a.n Komunitas Citarum Harum', 'bersikansampah.jpg', '2026-05-15 10:46:49'),
(9, 4, 'Subsidi Operasi Katarak Gratis Lansia Dhuafa', 'Kesehatan', 'Semarang', 'Katarak membuat puluhan lansia prasejahtera di pedesaan kehilangan penglihatan dan tidak lagi bisa bekerja untuk menghidupi keluarga. Biaya mandiri yang mahal membuat mereka pasrah. Setiap kelipatan donasi Rp3.500.000 akan digunakan membiayai tindakan operasi katarak lengkap beserta obat-obatan bagi satu lansia dhuafa.', 70000000.00, 45000000.00, 0.00, '2026-09-01', 'BRI 0021-01-998877-53-1 a.n Klinik Sehat Dhuafa', 'operasikatarak.webp', '2026-05-15 10:47:16'),
(10, 1, 'Patungan Ambulans Desa Siaga Korban Kecelakaan', 'Kesehatan', 'Gunungkidul', 'Puskesmas pembantu di desa pelosok ini belum memiliki mobil ambulans operasional yang memadai. Saat ada warga yang melahirkan secara darurat atau mengalami kecelakaan parah, mereka terpaksa dievakuasi menggunakan mobil bak terbuka. Dana yang terkumpul akan digunakan untuk membeli 1 unit mobil ambulans desa siaga.', 150000000.00, 95000000.00, 0.00, '2026-10-10', 'BCA 822-0911-344 a.n Yayasan Siaga Medika', 'ambulance.jpeg', '2026-05-15 10:47:16'),
(11, 1, 'Tanggap Darurat Gempa Bumi Bandung-Garut', 'Bencana', 'Garut', 'Gempa bumi tektonik yang melanda wilayah Kabupaten Bandung dan Garut telah mengakibatkan ribuan rumah warga rusak berat hingga roboh. Saat ini, para pengungsi sangat membutuhkan uluran tangan kita untuk pengadaan tenda darurat, alas tidur, selimut, susu bayi, serta paket makanan instan. Seluruh donasi yang terkumpul akan disalurkan langsung melalui posko relawan gabungan di lokasi bencana.', 100000000.00, 25000000.00, 0.00, '2026-06-15', 'BSI 777-1122-334 a.n Lembaga Respon Bencana Nusantara', 'gempabumigarut.jpeg', '2026-05-15 10:48:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `role` enum('donatur','penyelenggara') NOT NULL DEFAULT 'donatur',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `no_telepon`, `alamat`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin123', '08123456789', 'Jl. Sudirman No. 5', 'penyelenggara', '2026-05-15 08:11:45'),
(2, 'Yayasan Tanggap Darurat', 'admin@tanggapdarurat.org', 'yayasan123', '021-555123', 'Jl. Penyelamatan No. 10, Jakarta', 'penyelenggara', '2026-05-15 08:11:45'),
(3, 'Gerakan Membangun Desa', 'info@bangundesa.id', 'desa123', '022-777890', 'Jl. Kemakmuran No. 45, Bandung', 'penyelenggara', '2026-05-15 08:11:45'),
(4, 'putra', 'putra@gmail.com', 'putra123', '168746128982', 'jl.langit ke 9', 'penyelenggara', '2026-05-15 08:14:18'),
(7, 'a', 'a@gmail.com', '1', '126725767721', '', 'donatur', '2026-05-15 10:31:56');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `donasi`
--
ALTER TABLE `donasi`
  ADD PRIMARY KEY (`id_donasi`),
  ADD KEY `id_kampanye` (`id_kampanye`),
  ADD KEY `id_donatur` (`id_donatur`);

--
-- Indeks untuk tabel `kampanye`
--
ALTER TABLE `kampanye`
  ADD PRIMARY KEY (`id_kampanye`),
  ADD KEY `id_penyelenggara` (`id_penyelenggara`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `donasi`
--
ALTER TABLE `donasi`
  MODIFY `id_donasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `kampanye`
--
ALTER TABLE `kampanye`
  MODIFY `id_kampanye` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `donasi`
--
ALTER TABLE `donasi`
  ADD CONSTRAINT `donasi_ibfk_1` FOREIGN KEY (`id_kampanye`) REFERENCES `kampanye` (`id_kampanye`) ON DELETE CASCADE,
  ADD CONSTRAINT `donasi_ibfk_2` FOREIGN KEY (`id_donatur`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kampanye`
--
ALTER TABLE `kampanye`
  ADD CONSTRAINT `kampanye_ibfk_1` FOREIGN KEY (`id_penyelenggara`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
