<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../config/koneksi.php';

$search = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$search_sql = ($search != '') ? "WHERE LOWER(nama_barang) LIKE LOWER('%" . mysqli_real_escape_string($conn, $search) . "%')" : "";

$query = mysqli_query($conn, "SELECT * FROM barang $search_sql");

ob_start(); // Mulai buffering
?>

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

    <div class="card-container">
        <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <div class="card">
                <label>
                    <input type="checkbox" class="cb-barang" id="cb_<?= $row['id'] ?>" name="barang_id[]" value="<?= $row['id'] ?>">
                    <strong><?= htmlspecialchars($row['nama_barang']) ?></strong>
                </label>
                <img src="../uploads/<?= htmlspecialchars($row['gambar']) ?>" alt="Gambar <?= htmlspecialchars($row['nama_barang']) ?>" class="gambar-barang">
                <div>Stok: <?= $row['stok'] ?></div>
                <div>Kondisi: <?= htmlspecialchars($row['kondisi']) ?></div>
                <div class="input-wrapper">
                    <label>Jumlah:</label>
                    <input type="number" name="jumlah[<?= $row['id'] ?>]" id="jumlah_<?= $row['id'] ?>" class="jumlah-input" min="1" style="display:none;">
                </div>
                <div class="input-wrapper">
                    <label>Keterangan:</label>
                    <input type="text" name="keterangan[<?= $row['id'] ?>]" id="ket_<?= $row['id'] ?>" class="keterangan-input" style="display:none;">
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <button type="submit" style="margin-top: 1.5rem;">Proses Pengembalian</button>
</form>

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
                selections[id] = { jumlah, keterangan: ket };
            });
            localStorage.setItem(storageKey, JSON.stringify(selections));
        }

        document.getElementById('pengembalianForm').addEventListener('submit', function () {
            const selections = JSON.parse(localStorage.getItem(storageKey) || '{}');
            for (const id in selections) {
                this.innerHTML += `<input type="hidden" name="barang_id[]" value="${id}">`;
                this.innerHTML += `<input type="hidden" name="jumlah[${id}]" value="${selections[id].jumlah}">`;
                this.innerHTML += `<input type="hidden" name="keterangan[${id}]" value="${selections[id].keterangan}">`;
            }
            localStorage.removeItem(storageKey);
        });

        if (performance.navigation.type === 1) {
            localStorage.removeItem("kembaliSelection");
        }
    });
</script>

<?php
$content = ob_get_clean();
$title = "Form Pengembalian";
$page_title = "Pengembalian Barang";
$extra_css = '<link rel="stylesheet" href="../statics/pinjam.css">';
include 'base.php';
