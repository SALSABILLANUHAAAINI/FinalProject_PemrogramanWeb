<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../config/koneksi.php';

$search = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$search_sql = ($search !== "") ? "WHERE LOWER(nama_barang) LIKE LOWER('%" . mysqli_real_escape_string($conn, $search) . "%')" : "";

$query = mysqli_query($conn, "SELECT * FROM barang $search_sql");

ob_start();
?>

<h2>Form Peminjaman Barang</h2>

<?php if (isset($_SESSION['error_pinjam'])): ?>
    <div style="color: red; background-color: #ffe0e0; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
        <strong>Terjadi Kesalahan Peminjaman:</strong>
        <ul>
            <?php 
            if (is_array($_SESSION['error_pinjam'])):
                foreach ($_SESSION['error_pinjam'] as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
            <?php 
                endforeach;
            else: 
                // Jika string tunggal, tampilkan langsung
                echo '<li>' . htmlspecialchars($_SESSION['error_pinjam']) . '</li>';
            endif;
            ?>
        </ul>
    </div>
    <?php unset($_SESSION['error_pinjam']); ?>
<?php endif; ?>

<form method="GET" action="">
    <input type="text" name="cari" placeholder="Cari barang..." value="<?= htmlentities($search) ?>">
    <button type="submit">Cari</button>
</form>

<form method="POST" action="../proses/pinjam.php" id="pinjamForm">
    <input type="hidden" name="tanggal" value="<?= date('Y-m-d') ?>">
    <input type="text" value="<?= date('Y-m-d') ?>" disabled>

    <table class="tabel-barang">
        <thead>
            <tr>
                <th>Pilih</th><th>Nama</th><th>Stok</th><th>Kondisi</th><th>Jumlah</th><th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><input type="checkbox" class="cb-pinjam" id="cb_<?= $row['id'] ?>" name="barang_id[]" value="<?= $row['id'] ?>"></td>
                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td><?= $row['stok'] ?></td>
                <td><?= htmlspecialchars($row['kondisi']) ?></td>
                <td><input type="number" name="jumlah[<?= $row['id'] ?>]" id="jumlah_<?= $row['id'] ?>" class="jumlah-input" min="1" style="display:none;"></td>
                <td><input type="text" name="keterangan[<?= $row['id'] ?>]" id="ket_<?= $row['id'] ?>" class="keterangan-input" style="display:none;"></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <button type="submit">Proses Peminjaman</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const storageKey = 'pinjamSelection';
    const selections = JSON.parse(localStorage.getItem(storageKey) || '{}');

    for (const id in selections) {
        const cb = document.getElementById('cb_' + id);
        const jumlah = document.getElementById('jumlah_' + id);
        const ket = document.getElementById('ket_' + id);
        if (cb) cb.checked = true;
        if (jumlah) {
            jumlah.style.display = 'inline-block';
            jumlah.value = selections[id].jumlah;
        }
        if (ket) {
            ket.style.display = 'inline-block';
            ket.value = selections[id].keterangan;
        }
    }

    document.querySelectorAll('.cb-pinjam').forEach(cb => {
        cb.addEventListener('change', () => {
            const id = cb.value;
            const jumlah = document.getElementById('jumlah_' + id);
            const ket = document.getElementById('ket_' + id);
            if (cb.checked) {
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
        document.querySelectorAll('.cb-pinjam:checked').forEach(cb => {
            const id = cb.value;
            const jumlah = document.getElementById('jumlah_' + id).value;
            const ket = document.getElementById('ket_' + id).value;
            selections[id] = { jumlah, keterangan: ket };
        });
        localStorage.setItem(storageKey, JSON.stringify(selections));
    }

    const form = document.getElementById('pinjamForm');
    form.addEventListener('submit', function () {
        const selections = JSON.parse(localStorage.getItem(storageKey) || '{}');
        for (const id in selections) {
            if (!document.getElementById('cb_' + id)) {
                this.innerHTML += <input type="hidden" name="barang_id[]" value="${id}">;
                this.innerHTML += <input type="hidden" name="jumlah[${id}]" value="${selections[id].jumlah}">;
                this.innerHTML += <input type="hidden" name="keterangan[${id}]" value="${selections[id].keterangan}">;
            }
        }
        localStorage.removeItem(storageKey);
    });

    if (performance.navigation.type === 1) {
        localStorage.removeItem(storageKey);
    }
});
</script>

<?php
$content = ob_get_clean();
$title = "Form Peminjaman";
$page_title = "Peminjaman Barang";
$extra_css = '<link rel="stylesheet" href="../statics/pinjam.css">';
include 'base.php';
