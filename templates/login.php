<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link rel="stylesheet" href="../statics/login.css">
</head>
<body class="login-body">
    <div class="container">
        <h2 class="title">Log In</h2>
        <form method="POST" action="../proses/login.php">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <a class="link" href="register.php">Tambah User Baru</a>

            <button type="submit" class="button">LOGIN</button>
        </form>
    </div>
</body>
</html>
