<?php
include '../config/koneksi.php';

$nama = $_POST['nama'];
$stok_awal = (int)$_POST['stok']; // ini adalah stok awal
$kondisi = $_POST['kondisi'];
$stok = $stok_awal; // stok sisa awalnya sama dengan stok_awal

mysqli_query($conn, "INSERT INTO barang (nama_barang, stok_awal, stok, kondisi) 
                     VALUES ('$nama', $stok_awal, $stok, '$kondisi')");

header("Location: ../templates/dataBarang.php?success=1");
?>


