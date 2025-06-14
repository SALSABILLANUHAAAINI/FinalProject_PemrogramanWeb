<?php
    include '../config/koneksi.php';

    $nama = $_POST['nama'];
   $stok_awal = (int)$_POST['stok'];
    $stok = $stok_awal;
    $kondisi = $_POST['kondisi'];
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $folder = '../uploads/';


    $gambar_baru = uniqid() . "_" . $gambar;

    if (move_uploaded_file($tmp, $folder . $gambar_baru)) {
        $query = "INSERT INTO barang (nama_barang, stok, kondisi, gambar) 
                VALUES ('$nama', $stok, '$kondisi', '$gambar_baru')";
        
        if (mysqli_query($conn, $query)) {
            header("Location: ../templates/dashboard_admin.php");
            exit;
        } else {
            echo "Gagal menyimpan data: " . mysqli_error($conn);
        }
    } else {
        echo "Gagal mengupload gambar.";
    }
    mysqli_query($conn, "INSERT INTO barang (nama_barang, stok_awal,stok, kondisi) VALUES ('$nama',$stok_awal, $stok, '$kondisi')");

header("Location: ../templates/dataBarang.php?success=1");
?>
