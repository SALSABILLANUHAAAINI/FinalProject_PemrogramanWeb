<?php
include '../config/koneksi.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$stok = (int) $_POST['stok'];
$stok_awal = (int) $_POST['stok_awal'];
$kondisi = $_POST['kondisi'];

$new_stok_awal = $stok > $stok_awal ? $stok : $stok_awal;

$gambar = '';
if (!empty($_FILES['gambar']['name'])) {
    $gambar = time() . '_' . basename($_FILES['gambar']['name']);
    $upload_path = '../uploads/' . $gambar;
    move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path);

    // Update semua termasuk gambar
    $sql = "UPDATE barang SET nama_barang='$nama', stok=$stok, stok_awal=$new_stok_awal, kondisi='$kondisi', gambar='$gambar' WHERE id=$id";
} else {
    // Update tanpa ubah gambar
    $sql = "UPDATE barang SET nama_barang='$nama', stok=$stok, stok_awal=$new_stok_awal, kondisi='$kondisi' WHERE id=$id";
}

mysqli_query($conn, $sql);

header("Location: ../templates/dataBarang.php?update=1");