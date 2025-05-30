<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="../statics/style.css">
</head>
<body>
    <h2>Tambah Barang</h2>
    <form action="../proses/tambah_barang.php" method="POST">
        <label>Nama Barang:</label><br>
        <input type="text" name="nama" required><br>
        <label>Stok:</label><br>
        <input type="number" name="stok" required><br>
        <label>Kondisi:</label><br>
        <input type="text" name="kondisi"><br><br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
