<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($judul ?? "Beranda"); ?> - Presensi Siswa</title>
    <link rel="stylesheet" href="<?php echo $base; ?>assets/style.css">
</head>
<body>

<header class="topbar">
    <strong>Presensi Siswa</strong>
    <span>
        Halo, <?php echo htmlspecialchars($_SESSION["user"]); ?> |
        <a href="<?php echo $base; ?>logout.php">Logout</a>
    </span>
</header>

<div class="wrapper">

    <?php require __DIR__ . "/sidebar.php"; ?>

    <main class="content"></main>