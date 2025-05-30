<?php
// surat_peminjaman.php?id=ID_TRANSAKSI
include '../config/koneksi.php';
$id = $_GET['id'];
$q = mysqli_query($conn, "SELECT t.*, b.nama_barang FROM transaksi t JOIN barang b ON t.barang_id = b.id WHERE t.id = $id");
$data = mysqli_fetch_assoc($q);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Surat Peminjaman</title>
</head>
<body>
    <h2 style="text-align:center;">Surat Peminjaman Barang</h2>
    <hr>
    <p>Barang: <?= $data['nama_barang'] ?></p>
    <p>Tanggal Pinjam: <?= $data['tanggal'] ?></p>
    <p>Keterangan: <?= $data['keterangan'] ?></p>
    <br><br>
    <p>Ditandatangani oleh,</p>
    <p><b>Admin</b></p>
</body>
</html>
