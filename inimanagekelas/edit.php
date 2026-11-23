<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";
require_once "../models/kelas.php";

$pdo   = (new Database())->getConnection();
$kelas = new Kelas($pdo);

// Ambil data kelas yang mau diedit
$id   = (int) ($_GET["id"] ?? 0);
$data = $kelas->find($id);

if (!$data) {
    $_SESSION["pesan"] = "Kelas tidak ditemukan.";
    header("Location: index.php");
    exit;
}

$errors  = [];
$nama    = $data["nama_kelas"] ?? "";
$jurusan = $data["jurusan"] ?? "";

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

    if (empty($errors)) {
        $kelas->update($id, $nama, $jurusan);
        $_SESSION["pesan"] = "Kelas berhasil diubah.";
        header("Location: index.php");
        exit;
    }
}

$judul = "Edit Kelas";
$base  = "../";
$menu  = "kelas";

require "../layout/header.php";
?>

<h2>Edit Kelas</h2>

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

    <button type="submit">Simpan Perubahan</button>
    <a href="index.php">Batal</a>
</form>

<?php require "../layout/footer.php"; ?>