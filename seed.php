<?php

require_once "config/database.php";

$pdo = (new Database())->getConnection();

// Akun admin awal (username unik, jadi kalau dijalankan dua kali akan error)
$hash = password_hash("husshusshuss", PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->execute(["L'Admin", $hash]);

echo "User admin dibuat.";