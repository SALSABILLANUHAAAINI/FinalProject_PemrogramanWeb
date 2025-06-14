<?php
session_start();
include '../config/koneksi.php';

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        die("Username dan password tidak boleh kosong. <a href='../templates/register.php'>Kembali</a>");
    }

    // Amankan input
    $username_safe = mysqli_real_escape_string($conn, $username);
    $password_safe = mysqli_real_escape_string($conn, $password);

    // Cek apakah username sudah ada
    $cekUser = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username_safe'");
    if (mysqli_num_rows($cekUser) > 0) {
        header("Location: ../templates/login.php?register_error=username");
        exit;
    }

    // Cek apakah password sama dengan milik user lain
    $cekPassword = mysqli_query($conn, "SELECT * FROM users WHERE password = '$password_safe'");
    if (mysqli_num_rows($cekPassword) > 0) {
        header("Location: ../templates/login.php?register_error=password");
        exit;
    }

    // Simpan user ke database
    $simpan = mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username_safe', '$password_safe')");
    if ($simpan) {
        $_SESSION['admin'] = $username;
        header("Location: ../templates/dashboard_admin.php");
        exit;
    } else {
        die("Gagal menyimpan data. Error: " . mysqli_error($conn));
    }

} else {
    // Kalau bukan POST, kembalikan ke login
    header("Location: ../templates/login.php");
    exit;
}
