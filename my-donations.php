<?php
session_start();
include 'koneksi.php';

// Proteksi: Hanya donatur login yang bisa melihat riwayat donasinya sendiri
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'donatur') {
    header("Location: login.php");
    exit;
}

$id_donatur = $_SESSION['id_user'];

// Query mengambil riwayat transaksi donatur dan di-JOIN ke tabel kampanye untuk mendapatkan judulnya
$query = "SELECT donasi.*, kampanye.judul FROM donasi 
          INNER JOIN kampanye ON donasi.id_kampanye = kampanye.id_kampanye 
          WHERE donasi.id_donatur = $id_donatur 
          ORDER BY donasi.tanggal_donasi DESC";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Donasi Saya - BantuSesama</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        /* Gaya visual pewarnaan status sesuai ketentuan bonus rubrik tugas */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            display: inline-block;
        }
        .status-verified { background-color: #27ae60; color: white; } /* Hijau = Sukses */
        .status-pending { background-color: #f39c12; color: white; }  /* Kuning = Pending */
        .status-ditolak { background-color: #c0392b; color: white; }  /* Merah = Ditolak */
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; color: #333; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; text-align: left; }
        th { background-color: #43827e; color: white; font-weight: 600; }
        tr:nth-child(even) { background-color: #f8f9fa; }
    </style>
</head>
<body>
    <header>
        <h1>BantuSesama</h1>
        <nav>
            <a href="index.php">Beranda</a>
            <span style="margin-left:15px; color:yellow;">Halo, <?= htmlspecialchars($_SESSION['nama']); ?>!</span>
            <a href="my-donations.php">Donasi Saya</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main style="padding: 40px 5%; min-height: 75vh;">
        <h2 style="color: white; margin-bottom: 10px; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">Riwayat Donasi Anda</h2>
        <p style="color: #e0e0e0; margin-bottom: 25px;">Berikut adalah daftar kontribusi penggalangan dana yang telah Anda kirimkan.</p>

        <?php if (mysqli_num_rows($result) > 0) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Kampanye</th>
                        <th>Nominal Donasi</th>
                        <th>Metode Pembayaran</th>
                        <th>Status Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : 
                        // Pengkondisian class warna badge berdasarkan status di database
                        $bg_class = 'status-pending';
                        if ($row['status'] === 'VERIFIED') $bg_class = 'status-verified';
                        if ($row['status'] === 'DITOLAK') $bg_class = 'status-ditolak';
                    ?>
                        <tr>
                            <td><?= date('d M Y, H:i', strtotime($row['tanggal_donasi'])); ?> WIB</td>
                            <td style="font-weight: 600; color: #2c3e50;"><?= htmlspecialchars($row['judul']); ?></td>
                            <td style="font-weight: bold; color: #27ae60;">Rp <?= number_format($row['nominal'], 0, ',', '.'); ?></td>
                            <td><?= htmlspecialchars($row['metode_pembayaran']); ?></td>
                            <td>
                                <span class="badge <?= $bg_class; ?>"><?= $row['status']; ?></span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <div style="background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.1); padding: 30px; text-align: center; color: #f0f0f0; border-radius: 8px; font-weight: 500;">
                Anda belum pernah mengirimkan donasi. Silakan pilih kampanye aktif di halaman beranda.
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; Praktikum Prog. Web UKDW</p>
    </footer>
</body>
</html>
