<?php
require_once "../config/auth.php";
require_once "../config/db.php";

$error = "";

// 1. Handle the internal "Staff Verification" if they are already logged in as Staff
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = md5(trim($_POST['password']));

    // Check if the credentials belong to a STAFF or ADMIN
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ? AND (role = 'staff' OR role = 'admin')");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        // Access Granted - Send to the actual verification tool
        header("Location: meal_card.php");
        exit();
    } else {
        $error = "❌ Access Denied: Invalid Staff Credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Staff Security Gate - HUCMS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background: #0a3d62;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .lock-icon {
            font-size: 50px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="lock-icon" style="text-align: center;">🔐</div>
        <h2 style="text-align: center; color: #0a3d62;">Staff Portal</h2>
        <p style="text-align: center; font-size: 14px; color: #666;">Please verify your staff identity to proceed to Meal Verification.</p>

        <?php if ($error): ?>
            <p class="error" style="text-align: center;"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST">
            <label style="font-size: 13px; font-weight: bold;">Staff Username</label>
            <input type="text" name="username" placeholder="Enter Username" required>

            <label style="font-size: 13px; font-weight: bold;">Staff Password</label>
            <input type="password" name="password" placeholder="Enter Password" required>

            <button type="submit" style="background: #2ecc71; margin-top: 15px;">Verify & Enter</button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="logout.php" style="color: #e74c3c; text-decoration: none; font-size: 13px;">Logout Student Session</a>
        </div>
    </div>

</body>

</html>