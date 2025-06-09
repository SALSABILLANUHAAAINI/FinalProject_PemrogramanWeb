<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah User</title>
    <link rel="stylesheet" href="../statics/register.css">
</head>
<body class="login-body">
    <div class="container">
        <h2 class="title">Tambah User Baru</h2>
        <form method="POST" action="../proses/register.php">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <a class="link" href="login.php">← Kembali ke Login</a>

            <button type="submit" class="button">REGISTER</button>
        </form>
    </div>
</body>
</html>
