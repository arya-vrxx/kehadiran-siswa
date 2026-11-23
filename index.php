<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login-dasar.php");
    exit;
}
?>

<button><a href="logout.php">Logout</a></button>

