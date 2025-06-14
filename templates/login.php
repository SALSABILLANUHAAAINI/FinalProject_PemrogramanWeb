<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Form Login/Register Dinamis</title>
  <link rel="stylesheet" href="../statics/login.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
</head>
<body>
  <?php if (isset($_GET['error'])): ?>
    <div class="popup-error">
      <div class="popup-content">
        <p>Login gagal! Username atau password salah.</p>
        <p>Periksa Kembali dan Coba Lagi.</p>
        <button onclick="closePopup()">Coba Lagi</button>
      </div>
    </div>
  <?php endif; ?>
  
  <!-- ✅ POPUP REGISTER ERROR -->
<?php if (isset($_GET['register_error'])): ?>
  <div class="popup-error" id="popupError">
    <div class="popup-content">
      <p>
        <?php
        if ($_GET['register_error'] === 'username') {
          echo "Username sudah digunakan.";
        } elseif ($_GET['register_error'] === 'password') {
          echo "Register Gagal!Password tidak boleh sama dengan Sebelumnya.";
        }
        ?>
      </p>
      <button onclick="closePopup()">Tutup</button>
    </div>
  </div>
<?php endif; ?>

<!-- ✅ POPUP REGISTER BERHASIL -->
<?php if (isset($_GET['register_success'])): ?>
  <div class="popup-error" id="popupError">
    <div class="popup-content">
      <p style="color: green;">Berhasil mendaftar! Silakan login.</p>
      <button onclick="closePopup()">Tutup</button>
    </div>
  </div>
<?php endif; ?>


  <div class="container" id="container">
    <!-- Form Login -->
    <div class="form-container sign-in-container">
      <form action="../proses/login.php" method="POST">
        <h2>Selamat Datang Kembali</h2>
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit">LOGIN</button>
      </form>
    </div>

    <!-- Form Register -->
    <div class="form-container sign-up-container">
      <form action="../proses/register.php" method="POST">
        <h2>Daftar Akun Baru</h2>
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit">REGISTER</button>
      </form>
    </div>

    <!-- Overlay Panel -->
    <div class="overlay-container">
      <div class="overlay">
        <div class="overlay-panel overlay-left">
          <h2>Sudah Punya Akun?</h2>
          <p>Silakan login di sini</p>
          <button class="ghost" id="loginBtn">LOGIN</button>
        </div>
        <div class="overlay-panel overlay-right">
          <h2>Halo Teman Baru!</h2>
          <p>Isi data untuk membuat akun baru</p>
          <button class="ghost" id="registerBtn">REGISTER</button>
        </div>
      </div>
    </div>
  </div>

  <script>
  const container = document.getElementById("container");
  const registerBtn = document.getElementById("registerBtn");
  const loginBtn = document.getElementById("loginBtn");

  registerBtn.addEventListener("click", () => {
    container.classList.add("right-panel-active");
  });

  loginBtn.addEventListener("click", () => {
    container.classList.remove("right-panel-active");
  });

  function closePopup() {
    const popup = document.querySelector('.popup-error');
    if (popup) popup.style.display = 'none';
  }

  // Auto close popup in 3 seconds
  window.addEventListener("DOMContentLoaded", () => {
    const popup = document.querySelector('.popup-error');
    if (popup) {
      setTimeout(() => {
        closePopup();
      }, 3000);
    }
  });
</script>

</body>
</html>
