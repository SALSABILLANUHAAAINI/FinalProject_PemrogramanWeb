<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <link rel="stylesheet" href="../statics/login.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body>
  <div class="container">
    <div class="left">
      <div class="login-box">
        <h2>Log In</h2>
        <form method="POST" action="../proses/login.php">
          <label for="username">Username</label>
          <input type="text" name="username" required>

          <label for="password">Password</label>
          <input type="password" name="password" required>

          <a class="link" href="register.php">Tambah User Baru</a>

          <button type="submit">LOGIN</button>
        </form>
      </div>
    </div>
    <div class="right">
      <!-- Gambar diatur sebagai background di CSS -->
    </div>
  </div>
</body>
</html>
