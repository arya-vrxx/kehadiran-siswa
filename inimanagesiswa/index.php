<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";
require_once "../models/siswa.php";

$pdo   = (new Database())->getConnection();
$siswa = new Siswa($pdo);

// Kata kunci pencarian (GET), kosong = tampilkan semua
$keyword = trim($_GET["q"] ?? "");

$dataSiswa = $siswa->allWithKelas($keyword);

// Pesan dari halaman tambah/edit/hapus (tampil sekali saja)
$pesan = $_SESSION["pesan"] ?? "";
unset($_SESSION["pesan"]);

$judul = "Kelola Siswa";
$base  = "../";
$menu  = "siswa";

require "../layout/header.php";
?>

<h2>Kelola Siswa</h2>

<?php if ($pesan != ""): ?>
    <p><strong><?php echo htmlspecialchars($pesan); ?></strong></p>
<?php endif; ?>

<p><a href="tambah.php">+ Tambah Siswa</a></p>

<form method="get">
    Cari (nama/NISN):
    <input type="text" name="q" value="<?php echo htmlspecialchars($keyword); ?>">
    <button type="submit">Cari</button>
    <a href="index.php">Reset</a>
</form>

<p>Total: <?php echo count($dataSiswa); ?> siswa</p>

<table border="1" cellpadding="5">
    <tr>
        <th>No</th>
        <th>NISN</th>
        <th>Nama</th>
        <th>Alamat</th>
        <th>Kelas</th>
        <th>Aksi</th>
    </tr>

    <?php if (empty($dataSiswa)): ?>
    <tr>
        <td colspan="6">
            <?php echo $keyword !== "" ? "Siswa tidak ditemukan." : "Belum ada data siswa."; ?>
        </td>
    </tr>
    <?php endif; ?>

    <?php foreach ($dataSiswa as $i => $row): ?>
    <tr>
        <td><?php echo $i + 1; ?></td>
        <td><?php echo htmlspecialchars($row["NISN"]); ?></td>
        <td><?php echo htmlspecialchars($row["Nama"]); ?></td>
        <td><?php echo htmlspecialchars($row["Alamat"] ?? ""); ?></td>
        <td><?php echo htmlspecialchars($row["nama_kelas"] ?? "-"); ?></td>
        <td>
            <a href="edit.php?nisn=<?php echo urlencode($row["NISN"]); ?>">Edit</a>

            <form method="post" action="hapus.php" style="display:inline"
                  onsubmit="return confirm('Yakin hapus siswa ini?');">
                <input type="hidden" name="nisn" value="<?php echo htmlspecialchars($row["NISN"]); ?>">
                <button type="submit">Hapus</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php require "../layout/footer.php"; ?>