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
require_once "../models/presensi.php";

$pdo      = (new Database())->getConnection();
$presensi = new Presensi($pdo);

$id = (int) ($_POST["id"] ?? 0);

if (!$presensi->find($id)) {
    $_SESSION["pesan"] = "Log presensi tidak ditemukan.";
} else {
    try {
        $presensi->delete($id);
        $_SESSION["pesan"] = "Log presensi berhasil dihapus.";
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $_SESSION["pesan"] = "Log presensi gagal dihapus.";
    }
}

header("Location: index.php");
exit;