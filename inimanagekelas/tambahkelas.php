<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: ../login.php");
    exit;
}

require_once "../Config/database.php";
require_once "../models/kelas.php";

$pdo   = (new Database())->getConnection();
$kelas = new Kelas($pdo);

$errors  = [];
$nama    = "";
$jurusan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama    = trim($_POST["nama_kelas"] ?? "");
    $jurusan = trim($_POST["jurusan"] ?? "");

    // Validasi
    if ($nama === "") {
        $errors[] = "Nama kelas wajib diisi.";
    } elseif (mb_strlen($nama) > 20) {
        $errors[] = "Nama kelas maksimal 20 karakter.";
    }

    if ($jurusan === "") {
        $errors[] = "Jurusan wajib diisi.";
    } elseif (mb_strlen($jurusan) > 20) {
        $errors[] = "Jurusan maksimal 20 karakter.";
    }

    // Kalau lolos validasi, simpan lalu kembali ke daftar
    if (empty($errors)) {
        $kelas->create($nama, $jurusan);
        $_SESSION["pesan"] = "Kelas berhasil ditambahkan.";
        header("Location: index.php");
        exit;
    }
}

$judul = "Tambah Kelas";
$base  = "../";
$menu  = "kelas";

require "../layout/header.php";
?>

<h2>Tambah Kelas</h2>

<?php if (!empty($errors)): ?>
    <ul style="color:red">
        <?php foreach ($errors as $e): ?>
            <li><?php echo htmlspecialchars($e); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post">
    Nama Kelas:
    <input type="text" name="nama_kelas" value="<?php echo htmlspecialchars($nama); ?>"><br><br>

    Jurusan:
    <input type="text" name="jurusan" value="<?php echo htmlspecialchars($jurusan); ?>"><br><br>

    <button type="submit">Simpan</button>
    <a href="index.php">Batal</a>
</form>

<?php require "../layout/footer.php"; ?>