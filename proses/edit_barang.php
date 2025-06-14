<?php
include '../config/koneksi.php';

$id = $_POST['id'];
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$stok = (int) $_POST['stok'];
$kondisi = mysqli_real_escape_string($conn, $_POST['kondisi']);

$gambarBaru = $_FILES['gambar']['name'];
$tmpGambar = $_FILES['gambar']['tmp_name'];

if ($gambarBaru) {
    $namaGambarBaru = time() . '_' . basename($gambarBaru);
    $tujuan = "../uploads/$namaGambarBaru";

    if (move_uploaded_file($tmpGambar, $tujuan)) {
        // Ambil nama gambar lama
        $old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gambar FROM barang WHERE id = $id"));
        $gambarLama = $old['gambar'];

        // Hapus gambar lama (jika ada dan bukan default)
        if ($gambarLama && file_exists("../uploads/$gambarLama")) {
            unlink("../uploads/$gambarLama");
        }

        // Update dengan gambar baru
        $query = "UPDATE barang SET nama_barang='$nama', stok=$stok, kondisi='$kondisi', gambar='$namaGambarBaru' WHERE id=$id";
    } else {
        echo "Gagal upload gambar";
        exit;
    }
} else {
    // Tidak ada gambar baru diupload
    $query = "UPDATE barang SET nama_barang='$nama', stok=$stok, kondisi='$kondisi' WHERE id=$id";
}

if (mysqli_query($conn, $query)) {
    header("Location: ../admin/dataBarang.php");
} else {
    echo "Gagal update data!";
}
?>
