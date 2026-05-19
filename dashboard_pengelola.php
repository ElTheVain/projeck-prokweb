<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'donatur') {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$query = "SELECT d.*, c.judul_kampanye FROM donations d JOIN campaigns c ON d.campaign_id = c.id_campaign WHERE d.donor_id = $id_user ORDER BY d.tanggal DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Donasi Saya - BantuSesama</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <header>
        <h1>BantuSesama</h1>
        <nav>
            <a href="index.php">Beranda</a>
            <span style="margin-left:15px; color:yellow;">Halo, <?= $_SESSION['nama']; ?>!</span>
            <a href="my-donations.php">Donasi Saya</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h2>Donasi Saya</h2>
        <table border="1" style="width:100%; border-collapse:collapse;">
            <tr>
                <th>Tanggal</th>
                <th>Kampanye</th>
                <th>Nominal</th>
                <th>Metode</th>
                <th>Status</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= date('d M Y', strtotime($row['tanggal'])); ?></td>
                <td><?= $row['judul_kampanye']; ?></td>
                <td>Rp <?= number_format($row['nominal'], 0, ',', '.'); ?></td>
                <td><?= $row['metode_pembayaran']; ?></td>
                <td>Diterima</td>
            </tr>
            <?php endwhile; ?>
        </table>
    </main>

    <footer>
        <p>&copy; Praktikum Prog. Web UKDW</p>
    </footer>
</body>
</html>