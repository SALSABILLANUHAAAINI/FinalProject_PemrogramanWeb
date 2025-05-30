<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: login.php");

include '../config/koneksi.php';

$search = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$search_sql = ($search != '') ? "WHERE LOWER(nama_barang) LIKE LOWER('%" . mysqli_real_escape_string($conn, $search) . "%')" : "";

$query = mysqli_query($conn, "SELECT * FROM barang $search_sql");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Form Pengembalian Barang</title>
    <link rel="stylesheet" href="../statics/style.css">
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const storageKey = "kembaliSelection";
        const saved = JSON.parse(localStorage.getItem(storageKey) || "{}");

        for (const id in saved) {
            const cb = document.getElementById('cb_' + id);
            const jumlah = document.getElementById('jumlah_' + id);
            const ket = document.getElementById('ket_' + id);
            if (cb) cb.checked = true;
            if (jumlah) {
                jumlah.style.display = 'inline-block';
                jumlah.value = saved[id].jumlah;
            }
            if (ket) {
                ket.style.display = 'inline-block';
                ket.value = saved[id].keterangan;
            }
        }

        document.querySelectorAll('.cb-barang').forEach(cb => {
            cb.addEventListener('change', function () {
                const id = this.value;
                const jumlah = document.getElementById('jumlah_' + id);
                const ket = document.getElementById('ket_' + id);
                if (this.checked) {
                    jumlah.style.display = 'inline-block';
                    ket.style.display = 'inline-block';
                } else {
                    jumlah.style.display = 'none';
                    ket.style.display = 'none';
                    jumlah.value = '';
                    ket.value = '';
                }
                saveSelections();
            });
        });

        document.querySelectorAll('.jumlah-input, .keterangan-input').forEach(input => {
            input.addEventListener('input', saveSelections);
        });

        function saveSelections() {
            const selections = {};
            document.querySelectorAll('.cb-barang:checked').forEach(cb => {
                const id = cb.value;
                const jumlah = document.getElementById('jumlah_' + id).value;
                const ket = document.getElementById('ket_' + id).value;
                selections[id] = { jumlah: jumlah, keterangan: ket };
            });
            localStorage.setItem(storageKey, JSON.stringify(selections));
        }

        const form = document.querySelector('form#pengembalianForm');
        form.addEventListener('submit', function () {
            const selections = JSON.parse(localStorage.getItem(storageKey) || '{}');
            for (const id in selections) {
                form.innerHTML += `<input type="hidden" name="barang_id[]" value="${id}">`;
                form.innerHTML += `<input type="hidden" name="jumlah[${id}]" value="${selections[id].jumlah}">`;
                form.innerHTML += `<input type="hidden" name="keterangan[${id}]" value="${selections[id].keterangan}">`;
            }
            localStorage.removeItem(storageKey);
        });

        if (performance.navigation.type === 1) {
            localStorage.removeItem("kembaliSelection");
        }
    });
    </script>
</head>
<body>
<nav>
    <a href="dashboard_admin.php">Dashboard</a> |
    <a href="pinjam.php">Peminjaman</a> |
    <a href="kembalikan.php">Pengembalian</a> |
    <a href="riwayat.php">Riwayat</a> |
    <a href="../logout.php">Logout</a>
</nav>
<hr>
<h2>Form Pengembalian Barang</h2>

<?php if (isset($_SESSION['error_pengembalian'])): ?>
    <div style="color: red; background-color: #ffe0e0; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
        <strong>Terjadi Kesalahan Pengembalian:</strong>
        <ul>
        <?php foreach ($_SESSION['error_pengembalian'] as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($_SESSION['error_pengembalian']); ?>
<?php endif; ?>

<form method="GET" action="">
    <input type="text" name="cari" placeholder="Cari barang..." value="<?= htmlentities($search) ?>">
    <button type="submit">Cari</button>
</form>

<form method="POST" action="../proses/kembalikan.php" id="pengembalianForm">
    <input type="hidden" name="tanggal" value="<?= date('Y-m-d') ?>">
    <input type="text" value="<?= date('Y-m-d') ?>" disabled>
    <br><br>

    <table border="1">
        <thead>
            <tr>
                <th>Pilih</th><th>Nama</th><th>Stok</th><th>Kondisi</th><th>Jumlah</th><th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><input type="checkbox" class="cb-barang" id="cb_<?= $row['id'] ?>" name="barang_id[]" value="<?= $row['id'] ?>"></td>
                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td><?= $row['stok'] ?></td>
                <td><?= htmlspecialchars($row['kondisi']) ?></td>
                <td><input type="number" name="jumlah[<?= $row['id'] ?>]" id="jumlah_<?= $row['id'] ?>" class="jumlah-input" min="1" style="display:none;width:60px;"></td>
                <td><input type="text" name="keterangan[<?= $row['id'] ?>]" id="ket_<?= $row['id'] ?>" class="keterangan-input" style="display:none;width:150px;"></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <br>
    <button type="submit">Proses Pengembalian</button>
</form>
</body>
</html>
