<?php
require_once 'config/database.php';

$pdo = (new Database())->getConnection();

echo "Koneksi berhasil";