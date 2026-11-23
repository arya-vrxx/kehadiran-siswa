<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";
require_once "../models/presensi.php";
require_once "../models/kelas.php";

$pdo      = (new Database())->getConnection();
$presensi = new Presensi($pdo);
$kelas    = new Kelas($pdo);

// Filter dari form (GET). Tanggal default = hari ini;
// kalau kolom tanggal dikosongkan lalu dicari, tampil semua tanggal.
$keyword = trim($_GET["q"] ?? "");
$tanggal = trim($_GET["tanggal"] ?? date("Y-m-d"));
$idKelas = trim($_GET["kelas"] ?? "");

$dataRekap = $presensi->rekap($keyword, $tanggal, $idKelas);
$dataKelas = $kelas->all();

// Pesan dari hapus (tampil sekali saja)
$pesan = $_SESSION["pesan"] ?? "";
unset($_SESSION["pesan"]);

$judul = "Rekap Presensi";
$base  = "../";
$menu  = "presensi";

require "../layout/header.php";
?>

<h2>Rekap Presensi</h2>

<?php if ($pesan != ""): ?>
    <p><strong><?php echo htmlspecialchars($pesan); ?></strong></p>
<?php endif; ?>

<p><a href="tapkartu.php">Simulasi Tap Kartu</a></p>

<form method="get">
    Cari (nama/NISN):
    <input type="text" name="q" value="<?php echo htmlspecialchars($keyword); ?>">

    Tanggal:
    <input type="date" name="tanggal" value="<?php echo htmlspecialchars($tanggal); ?>">

    Kelas:
    <select name="kelas">
        <option value="">Semua kelas</option>
        <?php foreach ($dataKelas as $k): ?>
            <option value="<?php echo $k["id_kelas"]; ?>"
                <?php echo ($idKelas === (string) $k["id_kelas"]) ? "selected" : ""; ?>>
                <?php echo htmlspecialchars($k["nama_kelas"] ?? ""); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Cari</button>
    <a href="index.php">Reset (hari ini)</a>
</form>

<p>Total: <?php echo count($dataRekap); ?> log presensi</p>

<table border="1" cellpadding="5">
    <tr>
        <th>No</th>
        <th>Waktu</th>
        <th>NISN</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Aksi</th>
    </tr>

    <?php if (empty($dataRekap)): ?>
    <tr>
        <td colspan="6">Tidak ada data presensi.</td>
    </tr>
    <?php endif; ?>

    <?php foreach ($dataRekap as $i => $row): ?>
    <tr>
        <td><?php echo $i + 1; ?></td>
        <td><?php echo htmlspecialchars(date("d-m-Y H:i:s", strtotime($row["Tanggal_Waktu"]))); ?></td>
        <td><?php echo htmlspecialchars($row["NISN"]); ?></td>
        <td><?php echo htmlspecialchars($row["Nama"] ?? "(tidak dikenal)"); ?></td>
        <td><?php echo htmlspecialchars($row["nama_kelas"] ?? "-"); ?></td>
        <td>
            <form method="post" action="hapus.php" style="display:inline"
                  onsubmit="return confirm('Yakin hapus log ini?');">
                <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                <button type="submit">Hapus</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php require "../layout/footer.php"; ?>