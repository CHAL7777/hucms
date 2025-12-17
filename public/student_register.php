<?php
require_once "../config/auth.php";
require_once "../config/db.php";

if ($_SESSION['role'] !== 'admin') {
    die("Access denied. Admin privileges required.");
}

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $full_name  = trim($_POST['full_name'] ?? '');
    // 1. Get the password from the form and encrypt it using MD5
    $password   = trim($_POST['password'] ?? '');

    if (empty($password)) {
        $msg = "⚠️ Password is required";
    } else {
        $check = $pdo->prepare("SELECT id FROM students WHERE student_id = ?");
        $check->execute([$student_id]);

        if ($check->rowCount() > 0) {
            $msg = "⚠️ Student already registered by this ID";
        } else {
            // 2. Add 'password' to the INSERT query
            $hashed_password = md5($password);
            $stmt = $pdo->prepare("INSERT INTO students (student_id, full_name, password) VALUES (?, ?, ?)");
            $stmt->execute([$student_id, $full_name, $hashed_password]);
            $msg = "✅ Student registered successfully with password";
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Student Registration</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="card">
        <h2>🎓 Register Student</h2>
        <form method="post">
            <input type="text" name="student_id" placeholder="Student ID" required>
            <input type="text" name="full_name" placeholder="Full Name" required>

            <input type="password" name="password" placeholder="Set Student Password" required>

            <button type="submit">Register</button>
        </form>
        <p><?= htmlspecialchars($msg) ?></p>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>

</html>