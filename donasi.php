<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'donatur') {
    echo "<script>alert('Anda wajib login sebagai Donatur!'); window.location.href='login.php';</script>";
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$campaign = mysqli_fetch_assoc(mysqli_query($conn, "SELECT judul FROM kampanye WHERE id_kampanye = $id"));

if (isset($_POST['donasi'])) {
    $nominal = $_POST['nominal'];
    $metode = mysqli_real_escape_string($conn, $_POST['metode']);
    $pesan = mysqli_real_escape_string($conn, $_POST['pesan']);
    $donor_id = $_SESSION['id_user'];

    $nama_file = "bukti_" . time() . "_" . $_FILES['bukti']['name'];
    
    if (move_uploaded_file($_FILES['bukti']['tmp_name'], $nama_file)) {
        // Insert donasi dengan status PENDING
        mysqli_query($conn, "INSERT INTO donasi (id_kampanye, id_donatur, nominal, metode_pembayaran, pesan_dukungan, bukti_transfer, status) 
                             VALUES ($id, $donor_id, $nominal, '$metode', '$pesan', '$nama_file', 'PENDING')");
        
        // Naikkan kolom dana_pending di tabel kampanye
        mysqli_query($conn, "UPDATE kampanye SET dana_pending = dana_pending + $nominal WHERE id_kampanye = $id");

        echo "<script>alert('Donasi terkirim! Menunggu verifikasi pengelola.'); window.location.href='index.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kirim Donasi</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <header>
        <h1>BantuSesama</h1>
        <nav><a href="index.php">Batal</a></nav>
    </header>
    <main style="padding: 40px 5%;">
        <h2>Konfirmasi Donasi</h2>
        <p>Untuk Kampanye: <strong><?= htmlspecialchars($campaign['judul']); ?></strong></p>
        <form action="" method="POST" enctype="multipart/form-data" style="box-shadow:none;">
           <label>Nama Lengkap</label>
            <input type="text" value="<?= $_SESSION['nama']; ?>" readonly style="background:#e9ecef;">
            <label>Nominal Donasi (Min. Rp10.000)</label>
            <input type="number" name="nominal" min="10000" required>
            <label>Metode Pembayaran</label>
            <select name="metode" required>
                <option value="BCA">Bank BCA</option>
                <option value="Mandiri">Bank Mandiri</option>
                <option value="OVO/GoPay">E-Wallet (OVO/GoPay)</option>
            </select>
            <label>Pesan Dukungan</label>
            <textarea name="pesan" rows="3"></textarea>
            <label>Upload Bukti (.jpg/.png)</label>
            <input type="file" name="bukti" accept=".jpg,.jpeg,.png" required>
            <button type="submit" name="donasi" class="btn" style="background:#27ae60; color:white; margin-top:15px;">Kirim Donasi</button>
        </form>
    </main>
</body>
</html>
