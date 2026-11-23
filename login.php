<?php
session_start();

// Kalau sudah login, tidak perlu melihat form lagi
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}

require_once "config/database.php";

$pdo = (new Database())->getConnection();

$error    = "";
$username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $error = "Username dan password wajib diisi.";
    } else {
        // Ambil user dari database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(); // false kalau username tidak ada

        // Cocokkan password yang diketik dengan hash di database
        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true); // cegah session fixation
            $_SESSION["user"] = $user["username"];
            header("Location: index.php");
            exit;
        } else {
            $error = "Username atau password salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <?php if ($error != ""): ?>
        <p style="color:red"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post">
        Username: <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>"><br><br>
        Password: <input type="password" name="password"><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>