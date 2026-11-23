<?php
session_start();

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login-dasar.php");
    exit;
}

$error = "";

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username == "admin" && $password == "12345") {
        $_SESSION["user"] = $username;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<?php if (isset($_SESSION["user"])): ?>

    <h2>Selamat datang, <?php echo $_SESSION["user"]; ?>!</h2>
    <a href="?logout=1">Logout</a>

<?php else: ?>

    <h2>Login</h2>
    <?php if ($error != "") { echo "<p style='color:red'>$error</p>"; } ?>

    <form method="post">
        Username: <input type="text" name="username"><br><br>
        Password: <input type="password" name="password"><br><br>
        <button type="submit">Login</button>
    </form>

<?php endif; ?>

</body>
</html>