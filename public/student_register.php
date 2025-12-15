<?php
session_start();
require '../config/config.php';

/* Only admin can register students */
if (!isset($_SESSION['staff_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id']);
    $full_name  = trim($_POST['full_name']);

    // Check if student already exists
    $check = $pdo->prepare("SELECT id FROM students WHERE student_id = ?");
    $check->execute([$student_id]);

    if ($check->rowCount() > 0) {
        $msg = "⚠️ Student already registered";
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO students (student_id, full_name)
             VALUES (?, ?)"
        );
        $stmt->execute([$student_id, $full_name]);

        $msg = "✅ Student registered successfully";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="card">
    <h2>🎓 Register Student</h2>

    <form method="post">
        <input type="text" name="student_id" placeholder="Student ID" required>
        <input type="text" name="full_name" placeholder="Full Name" required>
        <button type="submit">Register</button>
    </form>

    <p><?= $msg ?></p>
</div>
</body>
</html>
