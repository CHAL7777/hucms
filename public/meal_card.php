<?php
session_start();
require '../config/config.php';

if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit;
}

$msg = "";
$staff_id = $_SESSION['staff_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $now = date('H:i:s');

    $mealStmt = $pdo->prepare(
        "SELECT * FROM meal_periods
         WHERE start_time <= ? AND end_time >= ?"
    );
    $mealStmt->execute([$now, $now]);
    $meal = $mealStmt->fetch();

    if (!$meal) {
        $msg = "⛔ No active meal period";
    } else {
        $stuStmt = $pdo->prepare("SELECT * FROM students WHERE student_id=?");
        $stuStmt->execute([$student_id]);
        $student = $stuStmt->fetch();

        if (!$student) {
            $msg = "❌ Student not found";
        } else {
            $check = $pdo->prepare(
                "SELECT id FROM meal_logs
                 WHERE student_id=? AND meal_period_id=?
                 AND DATE(timestamp)=CURDATE()"
            );
            $check->execute([$student['id'], $meal['id']]);

            $status = $check->rowCount() ? "duplicate" : "granted";
            $msg = $status === "granted"
                ? "✅ {$meal['name']} granted"
                : "⚠️ Duplicate attempt";

            $insert = $pdo->prepare(
                "INSERT INTO meal_logs
                 (student_id, meal_period_id, staff_id, status)
                 VALUES (?,?,?,?)"
            );
            $insert->execute([
                $student['id'],
                $meal['id'],
                $staff_id,
                $status
            ]);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Meal Card</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="card">
    <h2>🍽 Meal Card</h2>
    <form method="post">
        <input name="student_id" placeholder="Student ID" autofocus required>
        <button>Verify</button>
    </form>
    <p><?= $msg ?></p>
</div>
</body>
</html>
