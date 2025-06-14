<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../config/koneksi.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM barang WHERE id = $id"));

// Perhitungan
$stok_awal = (int)$data['stok_awal'];
$stok_sisa = (int)$data['stok'];
$dipinjam = $stok_awal - $stok_sisa;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Barang</title>
    <link rel="stylesheet" href="../statics/style.css">
</head>
<body>
    <h2>Edit Barang</h2>
    <form action="../proses/edit_barang.php" method="POST">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">
        
        <label>Nama Barang:</label><br>
        <input type="text" name="nama" value="<?= htmlspecialchars($data['nama_barang']) ?>"><br>
        
        <label>Stok Awal:</label><br>
        <input type="number" name="stok" value="<?= $stok_awal ?>" required><br>

        <label>Dipinjam:</label><br>
        <input type="number" value="<?= $dipinjam ?>" readonly><br>

        <label>Stok Tersisa (otomatis dihitung):</label><br>
        <input type="number" value="<?= $stok_sisa ?>" readonly><br>

        <label>Kondisi:</label><br>
        <input type="text" name="kondisi" value="<?= htmlspecialchars($data['kondisi']) ?>"><br><br>
        
        <button type="submit">Update</button>
    </form>
</body>
</html>

