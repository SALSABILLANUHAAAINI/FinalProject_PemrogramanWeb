<?php
    session_start();
    if (!isset($_SESSION['admin'])) {
        header("Location: ../templates/login.php");
        exit;
    }
    
    include '../config/koneksi.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tanggal = $_POST['tanggal'];
        $barang_ids = $_POST['barang_id'] ?? [];
        $jumlahs = $_POST['jumlah'] ?? [];
        $keterangans = $_POST['keterangan'] ?? [];
    
        foreach ($barang_ids as $id) {
            $id = intval($id);
            $jumlah = isset($jumlahs[$id]) ? intval($jumlahs[$id]) : 0;
            $keterangan = isset($keterangans[$id]) ? mysqli_real_escape_string($conn, $keterangans[$id]) : '';
    
            if ($jumlah <= 0) continue;
    
            // Cek stok
            $q = mysqli_query($conn, "SELECT stok FROM barang WHERE id = $id");
            $d = mysqli_fetch_assoc($q);
    
            if (!$d || $d['stok'] < $jumlah) {
                echo "Stok tidak cukup untuk barang ID $id. <a href='../templates/pinjam.php'>Kembali</a>";
                exit;
            }
    
            // Kurangi stok
            mysqli_query($conn, "UPDATE barang SET stok = stok - $jumlah WHERE id = $id");
    
            // Simpan transaksi DENGAN jumlah
            mysqli_query($conn, "INSERT INTO transaksi (barang_id, jenis, jumlah, tanggal, keterangan) 
                VALUES ($id, 'peminjaman', $jumlah, '$tanggal', '$keterangan')");
        }
    
        header("Location: ../templates/riwayat.php");
        exit;
    }
?>
