<?php
session_start();
require '../config/config.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 1. Prepare the query to find the user by username
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username=?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // 2. Direct comparison: Check if the user exists and the plain text password matches
    if ($user && $password === $user['password']) {
        // Set session variables upon successful login
        $_SESSION['staff_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login | Cafe Manager</title>
    <style>
        /* Modern CSS resets and base styles */
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }

        p.subtitle {
            color: #777;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box; /* Ensures padding doesn't affect width */
        }

        input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 8px rgba(102, 126, 234, 0.3);
        }

        button {
            width: 100%;
            padding: 14px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            margin-top: 10px;
        }

        button:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }

        button:active {
            transform: translateY(0);
        }

        .error {
            background-color: #fff5f5;
            color: #c53030;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #feb2b2;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .footer-text {
            margin-top: 25px;
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>🔐 Cafe Manager</h2>
    <p class="subtitle">Please enter your credentials to continue</p>

    <?php if($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div> 
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter username" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter password" required>
        </div>

        <button type="submit">Login to Dashboard</button>
    </form>

    <div class="footer-text">
        &copy; <?= date('Y') ?> Ethiopia Cafe Management System
    </div>
</div>

</body>
</html>