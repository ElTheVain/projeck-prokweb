<?php
session_start();
include 'koneksi.php';

// Ambil parameter pencarian jika ada
$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($conn, $_GET['keyword']) : '';
$kategori = isset($_GET['kategori']) ? mysqli_real_escape_string($conn, $_GET['kategori']) : '';
$lokasi = isset($_GET['lokasi']) ? mysqli_real_escape_string($conn, $_GET['lokasi']) : '';

// Query dasar
$sql = "SELECT * FROM kampanye WHERE 1=1";

if (!empty($keyword)) { $sql .= " AND judul LIKE '%$keyword%'"; }
if (!empty($kategori)) { $sql .= " AND kategori = '$kategori'"; }
if (!empty($lokasi)) { $sql .= " AND lokasi LIKE '%$lokasi%'"; }

// Gunakan AND jika sebelumnya sudah ada klausa WHERE
$sql .= " AND batas_waktu >= NOW()";

// Aturan urutan dari dokumen: Deadline terdekat & Dana terkumpul terkecil
$sql .= " ORDER BY batas_waktu ASC, dana_terkumpul ASC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>BantuSesama - Beranda</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <header>
        <h1>BantuSesama</h1>
        <nav>
            <a href="index.php">Beranda</a>
            <?php if (isset($_SESSION['login'])) : ?>
                <span style="margin-left:15px; color:yellow;">Halo, <?= htmlspecialchars($_SESSION['nama']); ?>!</span>
                <?php if ($_SESSION['role'] == 'donatur') : ?>
                    <a href="my-donations.php">Donasi Saya</a>
                <?php else : ?>
                    <a href="manage-campaigns.php">Kelola Kampanye</a>
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            <?php else : ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <section class="search-container">
        <form action="index.php" method="GET" style="display:flex; flex-wrap:wrap; gap:10px; width:100%; box-shadow:none; margin:0; padding:0;">
            <input type="text" name="keyword" placeholder="Cari judul..." value="<?= htmlspecialchars($keyword); ?>">
            <select name="kategori">
                <option value="">Semua Kategori</option>
                <option value="Bencana" <?= $kategori=='Bencana'?'selected':''; ?>>Bencana</option>
                <option value="Pendidikan" <?= $kategori=='Pendidikan'?'selected':''; ?>>Pendidikan</option>
                <option value="Kesehatan" <?= $kategori=='Kesehatan'?'selected':''; ?>>Kesehatan</option>
                <option value="Lingkungan" <?= $kategori=='Lingkungan'?'selected':''; ?>>Lingkungan</option>
                <option value="Fasilitas Umum" <?= $kategori=='Fasilitas Umum'?'selected':''; ?>>Fasilitas Umum</option>
            </select>
            <input type="text" name="lokasi" placeholder="Cari lokasi..." value="<?= htmlspecialchars($lokasi); ?>">
            <button type="submit" class="btn-search">Cari</button>
        </form>
    </section>

    <main class="campaign-grid">
        <?php if (mysqli_num_rows($result) > 0) : ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : 
                $persen = $row['target_dana'] > 0 ? ($row['dana_terkumpul'] / $row['target_dana']) * 100 : 0;
                if ($persen > 100) $persen = 100;
            ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($row['gambar']); ?>" alt="Poster">
                    <div class="card-content">
                        <h3><?= htmlspecialchars($row['judul']); ?></h3>
                        <p><strong>Kategori:</strong> <?= htmlspecialchars($row['kategori']); ?> | <strong>Lokasi:</strong> <?= htmlspecialchars($row['lokasi']); ?></p>
                        <p>Target: Rp <?= number_format($row['target_dana'], 0, ',', '.'); ?></p>
                        <p>Terkumpul: Rp <?= number_format($row['dana_terkumpul'], 0, ',', '.'); ?></p>
                        <div style="background: #e0e0e0; border-radius: 10px; height: 12px; margin: 10px 0; overflow:hidden;">
                            <div style="background: #27ae60; width: <?= round($persen); ?>%; height: 100%;"></div>
                        </div>
                        <p style="font-size: 0.8rem; color: #777;">Deadline: <?= date('d M Y', strtotime($row['batas_waktu'])); ?></p>
                        <a href="detail.php?id=<?= $row['id_kampanye']; ?>" class="btn" style="margin-top: 15px; text-decoration: none;">Lihat Detail</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p style="grid-column: 1/-1; text-align:center; color:#666;">Kampanye tidak ditemukan.</p>
        <?php endif; ?>
    </main>
    <footer><p>&copy; Praktikum Prog. Web UKDW</p></footer>
</body>
</html>
