<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}
include '../config/koneksi.php';

// Statistik
$total_barang = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM barang"));
$total_pinjam = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM transaksi WHERE jenis = 'peminjaman'"));
$total_kembali = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM transaksi WHERE jenis = 'pengembalian'"));

// Riwayat terbaru
$riwayat = mysqli_query($conn, "
    SELECT t.id, b.nama_barang, t.tanggal, t.jenis, t.keterangan
    FROM transaksi t
    JOIN barang b ON t.barang_id = b.id
    ORDER BY t.tanggal DESC
    LIMIT 6
");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../statics/sidebar.css">
    <link rel="stylesheet" href="../statics/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header class="mobile-header">
    <button id="toggleSidebar" class="hamburger">
        <i class="fas fa-bars"></i>
    </button>
    <h2>Dashboard</h2>
</header>

<div class="sidebar" id="sidebar">
    <div class="profile">
        <div class="avatar"></div>
        <h3><?= $_SESSION['admin']; ?></h3>
        <p class="status">Online</p>
    </div>
    <hr>
    <nav class="nav-links">
        <a href="dashboard.php"><i class="fas fa-house"></i> Dashboard</a>
        <a href="dataBarang.php"><i class="fas fa-box"></i> Data Barang</a>
        <a href="pinjam.php"><i class="fas fa-box-open"></i> Pinjam Barang</a>
        <a href="kembalikan.php"><i class="fas fa-undo-alt"></i> Kembalikan Barang</a>
        <a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
</div>

<div class="content">
    <h2>Selamat datang, <?= $_SESSION['admin']; ?></h2>

    <div class="stats-container">
        <div class="stat-box blue">
            <h2><?= $total_barang ?></h2>
            <p>Total Barang</p>
        </div>
        <div class="stat-box yellow">
            <h2><?= $total_pinjam ?></h2>
            <p>Total Peminjaman</p>
        </div>
        <div class="stat-box red">
            <h2><?= $total_kembali ?></h2>
            <p>Total Pengembalian</p>
        </div>
    </div>

    <h3>Riwayat Transaksi Terbaru</h3>
    <table class="riwayat-table">
        <tr><th>Barang</th><th>Tanggal</th><th>Jenis</th><th>Keterangan</th></tr>
        <?php while($r = mysqli_fetch_assoc($riwayat)): ?>
        <tr>
            <td><?= htmlspecialchars($r['nama_barang']) ?></td>
            <td><?= $r['tanggal'] ?></td>
            <td><?= ucfirst($r['jenis']) ?></td>
            <td><?= htmlspecialchars($r['keterangan']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('show');
    });
</script>

</body>
</html>
