<?php
    session_start();
    include '../config/koneksi.php';

    $username = $_POST['username'];
    $password = $_POST['password']; // input dari user

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $data = mysqli_fetch_assoc($result);

    if ($data && $password == $data['password']) {
        $_SESSION['admin'] = $data['username'];
        header("Location: ../templates/dashboard_admin.php");
    } else {
          header("Location: ../templates/login.php?error=1");
          exit;
    }
?>
