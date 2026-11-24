<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";
require_once "../models/kelas.php";
require_once "../models/siswa.php";


$pdo   = (new Database())->getConnection();
$kelas = new Kelas($pdo);
$jumlah = new Siswa($pdo);

$dataKelas = $kelas->allWithTotalPresensi();

// Pesan dari halaman tambah/edit/hapus (tampil sekali saja)
$pesan = $_SESSION["pesan"] ?? "";
unset($_SESSION["pesan"]);

$judul = "Kelola Kelas";
$base  = "../";
$menu  = "kelas";

require "../layout/header.php";
?>

<h2>Kelola Kelas</h2>

<?php if ($pesan != ""): ?>
    <p><strong><?php echo htmlspecialchars($pesan); ?></strong></p>
<?php endif; ?>

<p><a href="tambahkelas.php">+ Tambah Kelas</a></p>

<table border="1" cellpadding="5">
    <tr>
        <th>No</th>
        <th>Nama Kelas</th>
        <th>Jurusan</th>
        <th>Total</th>
        <th>Aksi</th>
    </tr>

    <?php if (empty($dataKelas)): ?>
    <tr>
        <td colspan="5">Belum ada data kelas.</td>
    </tr>
    <?php endif; ?>

    <?php foreach ($dataKelas as $i => $row): ?>
    <tr>
        <td><?php echo $i + 1; ?></td>
        <td><?php echo htmlspecialchars($row["nama_kelas"] ?? ""); ?></td>
        <td><?php echo htmlspecialchars($row["jurusan"] ?? ""); ?></td>
        <td><?php echo (int) $row["jumlah_presensi"]; ?></td>
        <td>
            <a href="edit.php?id=<?php echo $row["id_kelas"]; ?>">Edit</a>

            <form method="post" action="hapus.php" style="display:inline"
                  onsubmit="return confirm('Yakin hapus kelas ini?');">
                <input type="hidden" name="id" value="<?php echo $row["id_kelas"]; ?>">
                <button type="submit">Hapus</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php require "../layout/footer.php"; ?>