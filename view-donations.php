<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'penyelenggara') { header("Location: login.php"); exit; }

$id_kampanye = mysqli_real_escape_string($conn, $_GET['id']);

// Proses Aksi Tombol Terima / Tolak
if (isset($_GET['aksi']) && isset($_GET['id_donasi'])) {
    $aksi = $_GET['aksi'];
    $id_donasi = $_GET['id_donasi'];
    $nominal = $_GET['amt'];

    if ($aksi == 'terima') {
        // Pindahkan dana dari pending ke terkumpul, ubah status jadi VERIFIED
        mysqli_query($conn, "UPDATE kampanye SET dana_terkumpul = dana_terkumpul + $nominal, dana_pending = dana_pending - $nominal WHERE id_kampanye = $id_kampanye");
        mysqli_query($conn, "UPDATE donasi SET status = 'VERIFIED' WHERE id_donasi = $id_donasi");
    } else if ($aksi == 'tolak') {
        // Kurangi dana pending tanpa menambah ke terkumpul, ubah status jadi DITOLAK
        mysqli_query($conn, "UPDATE kampanye SET dana_pending = dana_pending - $nominal WHERE id_kampanye = $id_kampanye");
        mysqli_query($conn, "UPDATE donasi SET status = 'DITOLAK' WHERE id_donasi = $id_donasi");
    }
    header("Location: view-donations.php?id=" . $id_kampanye);
    exit;
}

$donasi_res = mysqli_query($conn, "SELECT donasi.*, users.nama FROM donasi INNER JOIN users ON donasi.id_donatur = users.id_user WHERE id_kampanye = $id_kampanye");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Donasi</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <header>
        <h1>Verifikasi Transaksi</h1>
        <nav><a href="manage-campaigns.php">Kembali</a></nav>
    </header>
    <main style="padding: 40px 5%;">
        <h2>Daftar Sumbangan Masuk</h2>
        <table border="1" style="width:100%; border-collapse:collapse; margin-top:20px; background:white;">
            <tr style="background:#43827e; color:white;">
                <th style="padding:10px;">Nama Donatur</th>
                <th>Nominal</th>
                <th>Metode</th>
                <th>Bukti Transfer</th>
                <th>Status</th>
                <th>Tindakan</th>
            </tr>
            <?php while ($d = mysqli_fetch_assoc($donasi_res)) : ?>
            <tr>
                <td style="padding:10px;"><?= htmlspecialchars($d['nama']); ?></td>
                <td>Rp <?= number_format($d['nominal'],0,',','.'); ?></td>
                <td><?= htmlspecialchars($d['metode_pembayaran']); ?></td>
                <td style="text-align:center;"><a href="<?= htmlspecialchars($d['bukti_transfer']); ?>" target="_blank">Buka File Bukti</a></td>
                <td style="text-align:center; font-weight:bold;"><?= $d['status']; ?></td>
                <td style="text-align:center;">
                    <?php if ($d['status'] == 'PENDING') : ?>
                        <a href="view-donations.php?id=<?= $id_kampanye; ?>&aksi=terima&id_donasi=<?= $d['id_donasi']; ?>&amt=<?= $d['nominal']; ?>" style="color:green; font-weight:bold; margin-right:10px;">[Terima]</a>
                        <a href="view-donations.php?id=<?= $id_kampanye; ?>&aksi=tolak&id_donasi=<?= $d['id_donasi']; ?>&amt=<?= $d['nominal']; ?>" style="color:red; font-weight:bold;">[Tolak]</a>
                    <?php else : ?>
                        Selesai
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </main>
</body>
</html>
