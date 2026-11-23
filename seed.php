<?php
// Seeder: jalankan SETELAH database/schema.sql diimpor.
//   php seed.php   (atau buka lewat browser)
// Aman dijalankan berulang: data hanya dibuat kalau belum ada.

header("Content-Type: text/plain; charset=utf-8");

require_once "Config/database.php";

$pdo = (new Database())->getConnection();

// 1. Data awal kelas (hanya kalau tabel kelas masih kosong)
$jumlahKelas = (int) $pdo->query("SELECT COUNT(*) FROM kelas")->fetchColumn();

if ($jumlahKelas === 0) {
    $daftarKelas = [
        ["XI PPLG", "PPLG"],
        ["XI TJKT", "TJKT"],
        ["XI MPLB", "MPLB"],
        ["XI DKV",  "DKV"],
    ];

    $stmt = $pdo->prepare("INSERT INTO kelas (nama_kelas, jurusan) VALUES (?, ?)");
    foreach ($daftarKelas as [$nama, $jurusan]) {
        $stmt->execute([$nama, $jurusan]);
    }
    echo "Data kelas dibuat.\n";
} else {
    echo "Data kelas sudah ada, dilewati.\n";
}

// 2. Akun Administrator (hanya kalau belum ada)
$username = "admin";
$password = "12345678";

$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
$stmt->execute([$username]);

if ((int) $stmt->fetchColumn() === 0) {
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $hash]);
    echo "Akun admin dibuat.\n";
} else {
    echo "Akun admin sudah ada, dilewati.\n";
}