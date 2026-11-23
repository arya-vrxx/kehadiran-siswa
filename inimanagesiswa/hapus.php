<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: ../login.php");
    exit;
}

// Hanya menerima POST (dari tombol Hapus), bukan dari URL biasa
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit;
}

require_once "../config/database.php";
require_once "../models/siswa.php";

$pdo   = (new Database())->getConnection();
$siswa = new Siswa($pdo);

$nisn = $_POST["nisn"] ?? "";

if (!$siswa->find($nisn)) {
    $_SESSION["pesan"] = "Siswa tidak ditemukan.";
} else {
    $jumlah = $siswa->countPresensi($nisn);

    if ($jumlah > 0) {
        // Siswa yang sudah punya riwayat presensi tidak boleh dihapus
        $_SESSION["pesan"] = "Siswa tidak bisa dihapus karena sudah memiliki $jumlah data presensi.";
    } else {
        try {
            $siswa->delete($nisn);
            $_SESSION["pesan"] = "Siswa berhasil dihapus.";
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $_SESSION["pesan"] = "Siswa gagal dihapus.";
        }
    }
}

header("Location: index.php");
exit;