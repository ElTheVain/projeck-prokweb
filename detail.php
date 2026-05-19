<?php 
session_start(); 
include 'koneksi.php'; 

if (!isset($_GET['id'])) { 
    header("Location: index.php"); 
    exit; 
} 

$id = mysqli_real_escape_string($conn, $_GET['id']); 

$query = "SELECT kampanye.*, users.nama AS nama_penyelenggara FROM kampanye 
          INNER JOIN users ON kampanye.id_penyelenggara = users.id_user 
          WHERE kampanye.id_kampanye = '$id'"; 

$result = mysqli_query($conn, $query); 

if (mysqli_num_rows($result) === 0) { 
    echo "Kampanye tidak ditemukan."; 
    exit; 
} 

$campaign = mysqli_fetch_assoc($result); 

$persentase = $campaign['target_dana'] > 0 ? ($campaign['dana_terkumpul'] / $campaign['target_dana']) * 100 : 0; 
if ($persentase > 100) $persentase = 100; 
?> 
<!DOCTYPE html> 
<html lang="id"> 
<head> 
    <meta charset="UTF-8"> 
    <title><?= htmlspecialchars($campaign['judul']); ?></title> 
    <link rel="stylesheet" href="style/style.css"> 
</head> 
<body> 
    <header> 
        <h1>BantuSesama</h1> 
        <nav><a href="index.php">Kembali ke Beranda</a></nav> 
    </header> 

    <!-- PERBAIKAN: Menambahkan perataan kolom agar sejajar dari atas -->
    <main class="detail-wrapper" style="align-items: start; gap: 30px;"> 
        
        <!-- KOLOM 1: Gambar Poster -->
        <div class="col-img">
            <img src="gambar/<?= htmlspecialchars($campaign['gambar']); ?>" alt="Poster" style="width:100%; border-radius:10px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
        </div> 

        <!-- KOLOM 2: Kotak Informasi & Deskripsi Kampanye (Diubah menjadi Kontainer Kotak Terang Kontras) -->
        <div class="col-info" style="background: rgba(0, 0, 0, 0.65); padding: 30px; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); color: white;"> 
    <h2 style="color: #ffffff; margin-bottom: 15px; font-size: 1.8rem; font-weight: bold; line-height: 1.3;"><?= htmlspecialchars($campaign['judul']); ?></h2> 
    
    <!-- PERBAIKAN WARNA TEKS AGAR KONTRAS & JELAS -->
    <div style="font-size: 0.95rem; line-height: 1.8; color: #ffffff;">
        <p><strong style="color: #1abc9c;">Penyelenggara:</strong> <span style="color: #ffffff; font-weight: 500;"><?= htmlspecialchars($campaign['nama_penyelenggara']); ?></span></p> 
        <p><strong style="color: #1abc9c;">Lokasi:</strong> <span style="color: #ffffff; font-weight: 500;"><?= htmlspecialchars($campaign['lokasi']); ?></span> | <strong style="color: #1abc9c;">Kategori:</strong> <span style="color: #ffffff; font-weight: 500;"><?= htmlspecialchars($campaign['kategori']); ?></span></p> 
    </div>

    

    <hr style="margin: 20px 0; border: none; border-top: 1px solid rgba(150, 51, 51, 0.2);"> 
    
    <h3 style="color: #ffffff; margin-bottom: 12px; font-size: 1.25rem;">Deskripsi:</h3> 
    <p style="line-height: 1.7; color: #f5f5f5; text-align: justify; font-size: 0.98rem;">
        <?= nl2br(htmlspecialchars($campaign['deskripsi'])); ?>
    </p> 
</div> 

        <!-- KOLOM 3: Action Card & Progress Bar -->
        <div class="col-action"> 
            <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); color: #333;"> 
                <p>Target: <strong>Rp <?= number_format($campaign['target_dana'], 0, ',', '.'); ?></strong></p> 
                <p>Terkumpul: <strong style="color: #27ae60;">Rp <?= number_format($campaign['dana_terkumpul'], 0, ',', '.'); ?></strong></p> 
                
                <div class="progress-container" style="background:#e0e0e0; border-radius:20px; margin:15px 0; height:25px; overflow:hidden;"> 
                    <div class="progress-bar" style="width: <?= round($persentase); ?>%; background:#28a745; color:white; text-align:center; height:100%; display:flex; align-items:center; justify-content:center; font-weight: bold; font-size: 13px;"> 
                        <?= round($persentase); ?>% 
                    </div> 
                </div> 
                
                <p>Deadline: <strong><?= date('d M Y', strtotime($campaign['batas_waktu'])); ?></strong></p> 
                
                <hr style="margin: 15px 0; border: none; border-top: 1px solid #eeeeee;">
                
                <p style="font-size:0.85rem; color:#666; margin-bottom: 15px; line-height: 1.5;">Rekening Pembayaran:<br><strong style="color: #333; font-size: 0.9rem;"><?= htmlspecialchars($campaign['rekening_informasi']); ?></strong></p> 
                
                <a href="donasi.php?id=<?= $campaign['id_kampanye']; ?>" class="btn btn-donasi" style="text-decoration:none; display:block; text-align: center; font-weight: bold;">Donasi Sekarang</a> 
            </div> 
        </div> 

    </main> 

    <footer><p>&copy; Praktikum Prog. Web UKDW</p></footer> 
</body> 
</html>