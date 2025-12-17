<?php
// 1. Initialize session and database
session_start();
require_once "../config/db.php";

$error = ""; // Initialize to prevent "Undefined variable" warning

// 2. Handle the Login Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = md5(trim($_POST['password'] ?? '')); // Using MD5 as per your current system

    if (!empty($username) && !empty($password)) {
        try {
            // --- CHECK STAFF & ADMINS FIRST ---
            $stmt = $pdo->prepare("SELECT id, username, role FROM users WHERE username = ? AND password = ?");
            $stmt->execute([$username, $password]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Set Session Variables
                $_SESSION['staff_id'] = $user['id'];
                $_SESSION['user']     = $user['username'];
                $_SESSION['role']     = $user['role'];

                // REDIRECTION: Admin goes to Dashboard, Staff goes to Portal
                if ($user['role'] === 'admin') {
                    header("Location: dashboard.php");
                } else {
                    header("Location: staff_portal.php");
                }
                exit();
            }

            // --- CHECK STUDENTS SECOND ---
            $stmtStudent = $pdo->prepare("SELECT id, student_id, full_name FROM students WHERE student_id = ? AND password = ?");
            $stmtStudent->execute([$username, $password]);
            $student = $stmtStudent->fetch(PDO::FETCH_ASSOC);

            if ($student) {
                $_SESSION['student_db_id'] = $student['id'];
                $_SESSION['user']          = $student['full_name'];
                $_SESSION['role']          = 'student';

                header("Location: student_page.php");
                exit();
            } else {
                $error = "❌ Invalid username or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "⚠️ Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HUCMS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background: #5f6482ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 350px;
            text-align: center;
        }

        .login-card h2 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .login-card p {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .login-card input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .login-card button {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }

        .login-card button:hover {
            background: #2980b9;
        }

        .error-msg {
            background: #fab1a0;
            color: #d63031;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            margin-bottom: 15px;
            border: 1px solid #ff7675;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h2>HUCMS Login</h2>
        <p>Sign in to your account</p>

        <?php if (!empty($error)): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Username or Student ID" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>

</body>

</html>