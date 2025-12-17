<?php
session_start();
require '../config/config.php'; // Database connection

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id']);
    
    // Check if the student exists in the database
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();

    if ($student) {
        // Create a secure session for the student
        $_SESSION['student_db_id'] = $student['id'];
        $_SESSION['student_id']    = $student['student_id'];
        $_SESSION['student_name']  = $student['full_name'];
        $_SESSION['role']          = 'student';

        // Redirect to a student-specific dashboard
        header("Location: student_dashboard.php");
        exit;
    } else {
        // Message for unregistered students
        $error = "❌ Student not found. Please register first at the office.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login | Cafe System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #748db3 0%, #4a6fa5 100%); /* Blue theme for students */
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 { color: #333; margin-bottom: 10px; }
        p.subtitle { color: #666; margin-bottom: 30px; font-size: 14px; }

        input {
            width: 100%;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover { background-color: #0056b3; }

        .error-box {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
            border: 1px solid #f5c6cb;
        }

        .admin-link { margin-top: 20px; display: block; color: #888; text-decoration: none; font-size: 13px; }
    </style>
</head>
<body>

<div class="login-card">
    <div style="font-size: 50px; margin-bottom: 10px;">🎓</div>
    <h2>Student Access</h2>
    <p class="subtitle">Enter your ID to view your meal history</p>

    <?php if($error): ?>
        <div class="error-box"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="student_id" placeholder="Enter Student ID" required autofocus>
        <button type="submit">Login to Dashboard</button>
    </form>

    <a href="login.php" class="admin-link">Staff Login? Click here</a>
</div>

</body>
</html>