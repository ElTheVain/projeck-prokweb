<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'penyelenggara') {
    header("Location: login.php"); exit;
}

$id_user = $_SESSION['id_user'];
$result = mysqli_query($conn, "SELECT * FROM kampanye WHERE id_penyelenggara = $id_user ORDER BY batas_waktu DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pengelola</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <header>
        <h1>Panel Pengelola</h1>
        <nav>
            <a href="index.php">Beranda</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main style="padding: 40px 5%;">
        <h2>Kampanye Saya</h2>
        <table border="1" style="width:100%; border-collapse:collapse; margin-top:20px; background:white;">
            <tr style="background:#43827e; color:white;">
                <th style="padding:10px;">Judul Kampanye</th>
                <th>Kategori</th>
                <th>Target Dana</th>
                <th>Terkumpul</th>
                <th>Dana Pending</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td style="padding:10px;"><?= htmlspecialchars($row['judul']); ?></td>
                <td><?= htmlspecialchars($row['kategori']); ?></td>
                <td>Rp <?= number_format($row['target_dana'],0,',','.'); ?></td>
                <td style="color:green;">Rp <?= number_format($row['dana_terkumpul'],0,',','.'); ?></td>
                <td style="color:orange;">Rp <?= number_format($row['dana_pending'],0,',','.'); ?></td>
                <td style="text-align:center;"><a href="view-donations.php?id=<?= $row['id_kampanye']; ?>">Verifikasi Donasi</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </main>
</body>
</html>
