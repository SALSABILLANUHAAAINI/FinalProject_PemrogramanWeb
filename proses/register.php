<?php
session_start();
include '../config/koneksi.php';

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    echo "<pre>Form diterima\n";
    var_dump($_POST);
    echo "</pre>";
    // exit; // uncomment ini untuk cek isi POST dulu

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        die("Username dan password tidak boleh kosong. <a href='../templates/register.php'>Kembali</a>");
    }

    $username_safe = mysqli_real_escape_string($conn, $username);
    $password_safe = mysqli_real_escape_string($conn, $password);

    // Cek user sudah ada atau belum
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username_safe'");
    if (!$cek) {
        die("Query cek user gagal: " . mysqli_error($conn));
    }
    if (mysqli_num_rows($cek) > 0) {
        die("Username sudah digunakan. <a href='../templates/register.php'>Coba lagi</a>");
    }

    // Simpan password tanpa hash
    $simpan = mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username_safe', '$password_safe')");
    if ($simpan) {
        $_SESSION['admin'] = $username;
        header("Location: ../templates/dashboard_admin.php");
        exit;
    } else {
        die("Gagal menyimpan data. Error: " . mysqli_error($conn) . " <a href='../templates/register.php'>Coba lagi</a>");
    }

} else {
    header("Location: ../templates/register.php");
    exit;
}
?>
