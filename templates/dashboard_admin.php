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
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../statics/style.css">
</head>
<body>
<h2>Selamat datang, <?= $_SESSION['admin']; ?></h2>
<nav>
    <a href="tambah_barang.php">Tambah Barang</a> |
    <a href="pinjam.php">Pinjam Barang</a> |
    <a href="kembalikan.php">Kembalikan Barang</a> |
    <a href="riwayat.php">Riwayat</a> |
    <a href="../logout.php">Logout</a>
</nav>
<hr>

<form method="GET" action="">
    <input type="text" name="cari" placeholder="Cari barang..." value="<?= htmlentities($search); ?>">
    <button type="submit">Cari</button>
</form>

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
</body>
</html>
