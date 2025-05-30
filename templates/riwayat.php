<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../config/koneksi.php';

$jenis = isset($_GET['jenis']) ? $_GET['jenis'] : 'peminjaman';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Transaksi</title>
    <link rel="stylesheet" href="../statics/style.css">
</head>
<body>

<nav>
    <a href="dashboard_admin.php">Dashboard</a> |
    <a href="pinjam.php">Peminjaman</a> |
    <a href="kembalikan.php">Pengembalian</a> |
    <a href="riwayat.php">Riwayat</a> |
    <a href="../logout.php">Logout</a>
</nav>

<hr>

<h2>Riwayat Transaksi Barang</h2>

<div class="nav-tabs">
    <a href="?jenis=peminjaman" class="<?= $jenis === 'peminjaman' ? 'active' : '' ?>">Peminjaman</a>
    <a href="?jenis=pengembalian" class="<?= $jenis === 'pengembalian' ? 'active' : '' ?>">Pengembalian</a>
</div>

<table border="1" cellpadding="5">
    <tr>
        <th>Barang</th>
        <th>Jenis</th>
        <th>Jumlah</th>
        <th>Tanggal</th>
        <th>Keterangan</th>
    </tr>
    <?php
    $stmt = mysqli_prepare($conn, "SELECT t.*, b.nama_barang FROM transaksi t JOIN barang b ON t.barang_id = b.id WHERE t.jenis = ? ORDER BY t.tanggal DESC");
    mysqli_stmt_bind_param($stmt, "s", $jenis);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['nama_barang']) . "</td>
                <td>" . htmlspecialchars($row['jenis']) . "</td>
                <td>" . htmlspecialchars($row['jumlah']) . "</td>
                <td>" . htmlspecialchars($row['tanggal']) . "</td>
                <td>" . htmlspecialchars($row['keterangan']) . "</td>
              </tr>";
    }
    ?>
</table>

</body>
</html>
