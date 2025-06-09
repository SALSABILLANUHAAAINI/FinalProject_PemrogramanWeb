<?php
    session_start();
    if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
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

    ob_start(); // start content buffering
?>

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

<?php
$content = ob_get_clean(); // ambil hasil buffer
$title = "Dashboard Admin";
$page_title = "Dashboard";
include 'base.php';
