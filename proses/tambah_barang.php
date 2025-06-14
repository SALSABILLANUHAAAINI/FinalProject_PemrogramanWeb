<?php
include '../config/koneksi.php';

$nama = $_POST['nama'];
$stok = $_POST['stok'];
$kondisi = $_POST['kondisi'];

mysqli_query($conn, "INSERT INTO barang (nama_barang, stok, stok_awal, kondisi) VALUES ('$nama', $stok, $stok, '$kondisi')");

header("Location: ../templates/dataBarang.php?success=1");
?>

