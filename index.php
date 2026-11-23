<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";
require_once "models/kelas.php";

$pdo   = (new Database())->getConnection();
$kelas = new Kelas($pdo);

$dataKelas = $kelas->all();

// Variabel untuk layout
$judul = "Beranda";
$base  = "";
$menu  = "beranda";

require "layout/header.php";
?>

<h2>Daftar Kelas</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>No</th>
        <th>Nama Kelas</th>
        <th>Jurusan</th>
    </tr>

    <?php foreach ($dataKelas as $i => $row): ?>
    <tr>
        <td><?php echo $i + 1; ?></td>
        <td><?php echo htmlspecialchars($row["nama_kelas"] ?? ""); ?></td>
        <td><?php echo htmlspecialchars($row["jurusan"] ?? ""); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php require "layout/footer.php"; ?>