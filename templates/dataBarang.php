<?php
    session_start();
    if (!isset($_SESSION['admin'])) {
        header("Location: login.php");
        exit;
    }
    include '../config/koneksi.php';

    // Pagination & search
    $batas = 5;
    $halaman = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1;
    $halaman_awal = ($halaman - 1) * $batas;
    $search = isset($_GET['cari']) ? trim($_GET['cari']) : '';
    $search_sql = $search != "" ? "WHERE LOWER(nama_barang) LIKE LOWER('%$search%')" : "";

    $total = mysqli_query($conn, "SELECT COUNT(*) as jml FROM barang $search_sql");
    $total_data = mysqli_fetch_assoc($total)['jml'];
    $total_halaman = ceil($total_data / $batas);

    $query = mysqli_query($conn, "SELECT * FROM barang $search_sql LIMIT $halaman_awal, $batas");

    // Untuk modal edit
    $editId = isset($_GET['edit']) ? (int) $_GET['edit'] : null;
    $editData = null;
    if ($editId) {
        $res = mysqli_query($conn, "SELECT * FROM barang WHERE id = $editId");
        if ($res && mysqli_num_rows($res) > 0) {
            $editData = mysqli_fetch_assoc($res);
        }
    }

    ob_start(); // Start content buffer
?>

<h2>Selamat datang, <?= $_SESSION['admin']; ?></h2>

<form method="GET" action="">
    <input type="text" name="cari" placeholder="Cari barang..." value="<?= htmlentities($search); ?>">
    <button type="submit">Cari</button>
</form>

<button class="btn-tambah" onclick="openModal()">
    <i class="fas fa-plus"></i> Tambah Barang
</button>

<h3>Data Barang</h3>
<table>
    <tr><th>Nama</th><th>Stok</th><th>Kondisi</th><th>Aksi</th></tr>
    <?php while($row = mysqli_fetch_assoc($query)): ?>
        <tr>
            <td><?= htmlspecialchars($row['nama_barang']) ?></td>
            <td><?= $row['stok'] ?></td>
            <td><?= htmlspecialchars($row['kondisi']) ?></td>
            <td>
                <a href='dataBarang.php?edit=<?= $row['id'] ?>'>Edit</a> |
                <a href='../proses/hapus_barang.php?id=<?= $row['id'] ?>' onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<div>
    Halaman:
    <?php for($i = 1; $i <= $total_halaman; $i++): ?>
        <a href="?halaman=<?= $i ?>&cari=<?= urlencode($search) ?>" <?= $i == $halaman ? 'style="font-weight:bold;"' : '' ?>><?= $i ?></a>
    <?php endfor; ?>
</div>

<!-- Modal Tambah -->
<div id="modalTambahBarang" class="modal" style="display: none;">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h3>Tambah Barang</h3>
    <form action="../proses/tambah_barang.php" method="POST">
        <label>Nama Barang:</label><br>
        <input type="text" name="nama" required><br>
        <label>Stok:</label><br>
        <input type="number" name="stok" required><br>
        <label>Kondisi:</label><br>
        <input type="text" name="kondisi"><br><br>
        <button type="submit">Simpan</button>
    </form>
  </div>
</div>

<!-- Modal Edit -->
<?php if ($editData): ?>
<div id="modalEditBarang" class="modal" style="display:block;">
  <div class="modal-content">
    <span class="close" onclick="window.location.href='dataBarang.php'">&times;</span>
    <h3>Edit Barang</h3>
    <form action="../proses/edit_barang.php" method="POST">
        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
        <label>Nama Barang:</label><br>
        <input type="text" name="nama" value="<?= htmlspecialchars($editData['nama_barang']) ?>" required><br>
        <label>Stok:</label><br>
        <input type="number" name="stok" value="<?= $editData['stok'] ?>" required><br>
        <label>Kondisi:</label><br>
        <input type="text" name="kondisi" value="<?= htmlspecialchars($editData['kondisi']) ?>"><br><br>
        <button type="submit">Update</button>
    </form>
  </div>
</div>
<?php endif; ?>

<script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('show');
    });

    function openModal() {
        document.getElementById('modalTambahBarang').style.display = 'block';
    }
    function closeModal() {
        document.getElementById('modalTambahBarang').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('modalTambahBarang');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
    
</script>

<?php
$content = ob_get_clean(); // Ambil isi buffer
$title = "Data Barang";
$page_title = "Data Barang";
$extra_css = '<link rel="stylesheet" href="../statics/dataBarang.css">';
include 'base.php';
