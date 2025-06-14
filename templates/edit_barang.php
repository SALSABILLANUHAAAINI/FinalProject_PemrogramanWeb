<?php
    session_start();
    if (!isset($_SESSION['admin'])) {
        header("Location: login.php");
    }
    include '../config/koneksi.php';
    $id = $_GET['id'];
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM barang WHERE id = $id"));
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
        <input type="text" name="nama" value="<?= $data['nama_barang'] ?>"><br>
        <label>Stok:</label><br>
        <input type="number" name="stok" value="<?= $data['stok'] ?>"><br>
        <label>Kondisi:</label><br>
        <input type="text" name="kondisi" value="<?= $data['kondisi'] ?>"><br><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
edit_barang.php
