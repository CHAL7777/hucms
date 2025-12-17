<?php
require_once "../config/db.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['staff_id'] = $user['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Login</title>
</head>

<body>
    <div class="login-box">
        <h2>HUCMS Login</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <p class="error"><?= $error ?></p>
        </form>
    </div>
</body>

</html>