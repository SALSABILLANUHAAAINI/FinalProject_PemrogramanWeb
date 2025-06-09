<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../config/koneksi.php';

$jenis = isset($_GET['jenis']) ? $_GET['jenis'] : 'peminjaman';

ob_start();
?>

<h2>Riwayat Transaksi Barang</h2>

<div class="riwayat-nav">
    <a href="?jenis=peminjaman" class="<?= $jenis === 'peminjaman' ? 'active' : '' ?>">Peminjaman</a>
    <a href="?jenis=pengembalian" class="<?= $jenis === 'pengembalian' ? 'active' : '' ?>">Pengembalian</a>
</div>

<div class="table-wrapper">
    <table class="riwayat-table">
        <thead>
        <tr>
            <th>Barang</th>
            <th>Jenis</th>
            <th>Jumlah</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $stmt = mysqli_prepare($conn, "SELECT t.*, b.nama_barang FROM transaksi t JOIN barang b ON t.barang_id = b.id WHERE t.jenis = ? ORDER BY t.tanggal DESC");
        mysqli_stmt_bind_param($stmt, "s", $jenis);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['nama_barang']) . "</td>
                    <td>" . htmlspecialchars($row['jenis']) . "</td>
                    <td>" . htmlspecialchars($row['jumlah']) . "</td>
                    <td>" . htmlspecialchars($row['tanggal']) . "</td>
                    <td>" . htmlspecialchars($row['keterangan']) . "</td>
                </tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
$title = "Riwayat Transaksi";
$page_title = "Riwayat";
$extra_css = '<link rel="stylesheet" href="../statics/riwayat.css">';
include 'base.php';
