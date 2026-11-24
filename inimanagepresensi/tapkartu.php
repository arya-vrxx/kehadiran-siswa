<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";
require_once "../models/presensi.php";
require_once "../models/siswa.php";
require_once "../models/kelas.php";

$pdo      = (new Database())->getConnection();
$presensi = new Presensi($pdo);
$siswa    = new Siswa($pdo);
$kelas    = new Kelas($pdo);

// Pesan sukses dari redirect sebelumnya (tampil sekali saja)
$pesan = $_SESSION["pesan"] ?? "";
unset($_SESSION["pesan"]);

$error = "";
$nisn  = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nisn = trim($_POST["nisn"] ?? "");

    if ($nisn === "") {
        $error = "NISN wajib diisi.";
    } else {
        $dataSiswa = $siswa->find($nisn);

        if (!$dataSiswa) {
            $error = "Kartu tidak dikenal: NISN tidak terdaftar.";
        } elseif ($presensi->sudahTapHariIni($nisn)) {                          
            $error = $dataSiswa["Nama"] . " sudah tercatat hadir hari ini.";    
        } else {
            // Setiap tap dicatat sebagai satu baris presensi
            $presensi->create($nisn);

            $dataKelas = $kelas->find($dataSiswa["id_kelas"]);
            $namaKelas = $dataKelas["nama_kelas"] ?? "-";

            $_SESSION["pesan"] = $dataSiswa["Nama"] . " (" . $namaKelas . ") tercatat hadir pada " . date("H:i:s") . ".";
            header("Location: tapkartu.php");
            exit;
        }
    }
}

$judul = "Simulasi Tap Kartu";
$base  = "../";
$menu  = "presensi";

require "../layout/header.php";
?>

<h2>Simulasi Tap Kartu</h2>

<?php if ($pesan != ""): ?>
    <p><strong><?php echo htmlspecialchars($pesan); ?></strong></p>
<?php endif; ?>

<?php if ($error != ""): ?>
    <p style="color:red"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="post">
    NISN pada kartu:<br>
    <input type="text" name="nisn" maxlength="10" value="<?php echo htmlspecialchars($nisn); ?>" autofocus><br><br>

    <button type="submit">Tap Kartu</button>
    <a href="index.php">Lihat Rekap</a>
</form>

<?php require "../layout/footer.php"; ?>