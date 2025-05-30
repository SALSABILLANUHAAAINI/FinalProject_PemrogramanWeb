<?php
    include '../config/koneksi.php';

    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $stok = $_POST['stok'];
    $kondisi = $_POST['kondisi'];

    mysqli_query($conn, "UPDATE barang SET nama_barang='$nama', stok=$stok, kondisi='$kondisi' WHERE id=$id");
    header("Location: ../templates/dashboard_admin.php");
?>
