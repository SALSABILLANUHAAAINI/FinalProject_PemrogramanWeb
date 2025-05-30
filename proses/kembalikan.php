<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../templates/login.php");
    exit;
}

include '../config/koneksi.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    $barang_ids = $_POST['barang_id'] ?? [];
    $jumlahs = $_POST['jumlah'] ?? [];
    $keterangans = $_POST['keterangan'] ?? [];

    foreach ($barang_ids as $id) {
        $id = intval($id);
        $jumlah_kembali = isset($jumlahs[$id]) ? intval($jumlahs[$id]) : 0;
        $keterangan = isset($keterangans[$id]) ? mysqli_real_escape_string($conn, $keterangans[$id]) : '';

        if ($jumlah_kembali <= 0) continue;

        // Ambil data stok dan stok_awal dari tabel barang
        $query_barang = mysqli_query($conn, "SELECT stok, stok_awal FROM barang WHERE id = $id");
        $barang_data = mysqli_fetch_assoc($query_barang);
        if (!$barang_data) {
            $errors[] = "Barang ID $id tidak ditemukan.";
            continue;
        }

        $stok_sekarang = (int)$barang_data['stok'];
        $stok_awal     = (int)$barang_data['stok_awal'];
        $maksimal_bisa_kembali = $stok_awal - $stok_sekarang;

        if ($jumlah_kembali > $maksimal_bisa_kembali) {
            $errors[] = "Barang ID $id melebihi stok awal. Maksimal bisa dikembalikan: $maksimal_bisa_kembali, Dikirim: $jumlah_kembali";
            continue;
        }

        // Update stok
        mysqli_query($conn, "UPDATE barang SET stok = stok + $jumlah_kembali WHERE id = $id");

        // Simpan transaksi pengembalian
        mysqli_query($conn, "INSERT INTO transaksi (barang_id, jenis, jumlah, tanggal, keterangan) 
            VALUES ($id, 'pengembalian', $jumlah_kembali, '$tanggal', '$keterangan')");
    }

    if (!empty($errors)) {
        $_SESSION['error_pengembalian'] = $errors;
        header("Location: ../templates/kembalikan.php");
    } else {
        header("Location: ../templates/riwayat.php");
    }
    exit;
}
?>
