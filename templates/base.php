<?php
$current_page = basename($_SERVER['PHP_SELF']);
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= isset($title) ? $title : 'Dashboard' ?></title>
  <link rel="stylesheet" href="../statics/sidebar.css">
  <link rel="stylesheet" href="../statics/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
  <?php if (isset($extra_css)) echo $extra_css; ?>
</head>
<body>

<div class="mobile-header">
  <button id="toggleSidebar" class="hamburger">
    <i class="fas fa-bars"></i>
  </button>
  <h2><?= isset($page_title) ? $page_title : 'Dashboard' ?></h2>
</div>

<div class="main-wrapper">
  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="profile">
      <div class="avatar"></div>
      <h3><?= $_SESSION['admin'] ?? 'Admin' ?></h3>
      <p class="status">Online</p>
    </div>
    <nav class="nav-links">
      <a href="dashboard_admin.php" class="<?= $current_page == 'dashboard_admin.php' ? 'active' : '' ?>">
        <i class="fas fa-house"></i> Dashboard
      </a>
      <a href="dataBarang.php" class="<?= $current_page == 'dataBarang.php' ? 'active' : '' ?>">
        <i class="fas fa-box"></i> Data Barang
      </a>
      <a href="pinjam.php" class="<?= $current_page == 'pinjam.php' ? 'active' : '' ?>">
        <i class="fas fa-box-open"></i> Pinjam Barang
      </a>
      <a href="kembalikan.php" class="<?= $current_page == 'kembalikan.php' ? 'active' : '' ?>">
        <i class="fas fa-undo-alt"></i> Kembalikan Barang
      </a>
      <a href="riwayat.php" class="<?= $current_page == 'riwayat.php' ? 'active' : '' ?>">
        <i class="fas fa-history"></i> Riwayat
      </a>
      <a href="../logout.php" class="<?= $current_page == 'logout.php' ? 'active' : '' ?>">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <header class="topbar">
      <h2><?= isset($page_title) ? $page_title : 'Dashboard' ?></h2>
    </header>

    <section class="dashboard-section">
      <?php if (isset($content)) echo $content; ?>
    </section>
  </div>
</div>

<script>
  const toggleBtn = document.getElementById('toggleSidebar');
  const sidebar = document.getElementById('sidebar');
  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('show');
  });
</script>

</body>
</html>