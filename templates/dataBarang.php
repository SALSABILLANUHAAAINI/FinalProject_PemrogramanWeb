<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}
include '../config/koneksi.php';

// Pagination & search
$batas = 5;
$halaman = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1;
$halaman_awal = ($halaman - 1) * $batas;
$search = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$search_sql = $search != "" ? "WHERE LOWER(nama_barang) LIKE LOWER('%$search%')" : "";

$total = mysqli_query($conn, "SELECT COUNT(*) as jml FROM barang $search_sql");
$total_data = mysqli_fetch_assoc($total)['jml'];
$total_halaman = ceil($total_data / $batas);

$query = mysqli_query($conn, "SELECT * FROM barang $search_sql LIMIT $halaman_awal, $batas");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../statics/sidebar.css">
    <link rel="stylesheet" href="../statics/dataBarang.css">
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
        <a href="dashboard.php"><i class="fas fa-house"></i> Home</a>
        <a href="dataBarang.php"><i class="fas fa-box"></i> Data Barang</a>
        <a href="pinjam.php"><i class="fas fa-box-open"></i> Pinjam Barang</a>
        <a href="kembalikan.php"><i class="fas fa-undo-alt"></i> Kembalikan Barang</a>
        <a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
</div>

<div class="content">
    <h2>Selamat datang, <?= $_SESSION['admin']; ?></h2>

    <form method="GET" action="">
        <input type="text" name="cari" placeholder="Cari barang..." value="<?= htmlentities($search); ?>">
        <button type="submit">Cari</button>
    </form>
        <a href="tambah_barang.php" class="btn-tambah">
            <i class="fas fa-plus"></i> Tambah Barang
        </a>
    <h3>Data Barang</h3>
    <table border="1" cellpadding="5">
        <tr><th>Nama</th><th>Stok</th><th>Kondisi</th><th>Aksi</th></tr>
        <?php while($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td><?= $row['stok'] ?></td>
                <td><?= htmlspecialchars($row['kondisi']) ?></td>
                <td>
                    <a href='edit_barang.php?id=<?= $row['id'] ?>'>Edit</a> |
                    <a href='../proses/hapus_barang.php?id=<?= $row['id'] ?>' onclick="return confirm('Yakin hapus?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div>
        Halaman:
        <?php for($i = 1; $i <= $total_halaman; $i++): ?>
            <a href="?halaman=<?= $i ?>&cari=<?= urlencode($search) ?>" <?= $i == $halaman ? 'style="font-weight:bold;"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>
    </div>
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
