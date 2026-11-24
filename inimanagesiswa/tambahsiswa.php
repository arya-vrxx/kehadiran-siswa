<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";
require_once "../models/siswa.php";
require_once "../models/kelas.php";

$pdo   = (new Database())->getConnection();
$siswa = new Siswa($pdo);
$kelas = new Kelas($pdo);

// Pilihan untuk dropdown kelas
$dataKelas = $kelas->all();

$errors  = [];
$nisn    = "";
$nama    = "";
$alamat  = "";
$idKelas = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nisn    = trim($_POST["nisn"] ?? "");
    $nama    = trim($_POST["nama"] ?? "");
    $alamat  = trim($_POST["alamat"] ?? "");
    $idKelas = $_POST["id_kelas"] ?? "";

    // Validasi NISN
    if ($nisn === "") {
        $errors[] = "NISN wajib diisi.";
    } elseif (!ctype_digit($nisn)) {
        $errors[] = "NISN hanya boleh berisi angka.";
    } elseif (strlen($nisn) !== 10) {
        $errors[] = "NISN harus tepat 10 digit.";
    } elseif ($siswa->find($nisn)) {
        $errors[] = "NISN sudah terdaftar.";
    }

    // Validasi nama
    if ($nama === "") {
        $errors[] = "Nama wajib diisi.";
    } elseif (mb_strlen($nama) > 100) {
        $errors[] = "Nama maksimal 100 karakter.";
    }

    // Validasi alamat (boleh kosong)
    if (mb_strlen($alamat) > 250) {
        $errors[] = "Alamat maksimal 250 karakter.";
    }

    // Validasi kelas: harus dipilih dan harus ada di database
    if ($idKelas === "") {
        $errors[] = "Kelas wajib dipilih.";
    } elseif (!ctype_digit($idKelas) || !$kelas->find($idKelas)) {
        $errors[] = "Kelas tidak valid.";
    }

    if (empty($errors)) {
        $siswa->create($nisn, $nama, $alamat, (int) $idKelas);
        $_SESSION["pesan"] = "Siswa berhasil ditambahkan.";
        header("Location: index.php");
        exit;
    }
}

$judul = "Tambah Siswa";
$base  = "../";
$menu  = "siswa";

require "../layout/header.php";
?>

<h2>Tambah Siswa</h2>

<?php if (!empty($errors)): ?>
    <ul style="color:red">
        <?php foreach ($errors as $e): ?>
            <li><?php echo htmlspecialchars($e); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post">
    NISN:<br>
    <input type="text" name="nisn" maxlength="10" value="<?php echo htmlspecialchars($nisn); ?>"><br><br>

    Nama:<br>
    <input type="text" name="nama" value="<?php echo htmlspecialchars($nama); ?>"><br><br>

    Alamat:<br>
    <textarea name="alamat" rows="3" cols="30"><?php echo htmlspecialchars($alamat); ?></textarea><br><br>

    Kelas:<br>
    <select name="id_kelas">
        <option value="">-- Pilih Kelas --</option>
        <?php foreach ($dataKelas as $k): ?>
            <option value="<?php echo $k["id_kelas"]; ?>"
                <?php echo ((string) $idKelas === (string) $k["id_kelas"]) ? "selected" : ""; ?>>
                <?php echo htmlspecialchars($k["nama_kelas"] ?? ""); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Simpan</button>
    <a href="index.php">Batal</a>
</form>

<?php require "../layout/footer.php"; ?>