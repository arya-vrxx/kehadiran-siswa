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
require_once "../models/kelas.php";

$pdo   = (new Database())->getConnection();
$kelas = new Kelas($pdo);

$id = (int) ($_POST["id"] ?? 0);

if (!$kelas->find($id)) {
    $_SESSION["pesan"] = "Kelas tidak ditemukan.";
} else {
    $jumlah = $kelas->countSiswa($id);

    if ($jumlah > 0) {
        // Kelas yang masih punya siswa tidak boleh dihapus
        $_SESSION["pesan"] = "Kelas tidak bisa dihapus karena masih memiliki $jumlah siswa.";
    } else {
        try {
            $kelas->delete($id);
            $_SESSION["pesan"] = "Kelas berhasil dihapus.";
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $_SESSION["pesan"] = "Kelas gagal dihapus.";
        }
    }
}

header("Location: index.php");
exit;