<?php
require_once "../config/auth.php";
require_once "../config/db.php";

if ($_SESSION['role'] !== 'admin') {
    header("Location: staff_portal.php");
    exit();
}

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $full_name  = trim($_POST['full_name'] ?? '');
    $password   = trim($_POST['password'] ?? '');

    if (!empty($student_id) && !empty($full_name) && !empty($password)) {
        $check = $pdo->prepare("SELECT id FROM students WHERE student_id = ?");
        $check->execute([$student_id]);

        if ($check->rowCount() > 0) {
            $msg = "⚠️ Error: Student ID already exists.";
        } else {
            $hashed_pass = md5($password);
            $stmt = $pdo->prepare("INSERT INTO students (student_id, full_name, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$student_id, $full_name, $hashed_pass])) {
                $msg = "✅ Student registered successfully!";
            } else {
                $msg = "❌ Error saving to database.";
            }
        }
    } else {
        $msg = "⚠️ All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Register Student</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="navbar">
        <a href="dashboard.php">Back to Dashboard</a>
    </div>

    <div class="card" style="max-width: 400px; margin: 50px auto;">
        <h2>🎓 Register New Student</h2>
        <p>Set a password the student will use at the cafeteria.</p>
        <hr>
        <form method="POST">
            <div class="form-group">
                <label>Student ID</label>
                <input type="text" name="student_id" placeholder="e.g. 2024001" required>
            </div>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" placeholder="Enter name" required>
            </div>
            <div class="form-group">
                <label>Student Password</label>
                <input type="password" name="password" placeholder="Enter student password" required>
            </div>
            <button type="submit" class="btn-primary">Register Student</button>
        </form>
        <p style="text-align:center; font-weight:bold;"><?= $msg ?></p>
    </div>
</body>

</html>