<?php
include '../config/koneksi.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$stok_awal_baru = (int)$_POST['stok'];
$kondisi = $_POST['kondisi'];

// Ambil stok lama dan stok_awal lama
$result = mysqli_query($conn, "SELECT stok, stok_awal FROM barang WHERE id = $id");
$data_lama = mysqli_fetch_assoc($result);

$stok_lama = (int)$data_lama['stok'];
$stok_awal_lama = (int)$data_lama['stok_awal'];

// Hitung jumlah barang yang sedang dipinjam
$dipinjam = $stok_awal_lama - $stok_lama;

// Hitung stok baru (tidak boleh negatif)
$stok_baru = $stok_awal_baru - $dipinjam;
if ($stok_baru < 0) $stok_baru = 0;

// Update ke database
mysqli_query($conn, "UPDATE barang SET 
    nama_barang = '$nama', 
    stok_awal = $stok_awal_baru,
    stok = $stok_baru, 
    kondisi = '$kondisi' 
WHERE id = $id");

header("Location: ../templates/dataBarang.php?update=success");
?>

