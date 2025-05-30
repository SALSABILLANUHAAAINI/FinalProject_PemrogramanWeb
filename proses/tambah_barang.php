<?php
    include '../config/koneksi.php';

    $nama = $_POST['nama'];
    $stok = $_POST['stok'];
    $kondisi = $_POST['kondisi'];

    mysqli_query($conn, "INSERT INTO barang (nama_barang, stok, kondisi) VALUES ('$nama', $stok, '$kondisi')");
header("Location: ../templates/dashboard_admin.php");
?>
