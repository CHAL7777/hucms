<?php
require_once "../config/auth.php"; // Already starts session and checks login
require_once "../config/db.php";   // Already creates $pdo

$msg = "";
$staff_id = $_SESSION['staff_id']; // Now safe to use

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id']);
    // 1. Get the password from the form and hash it
    $password = md5(trim($_POST['password']));
    $now = date('H:i:s');

    // 2. Check for active meal period
    $mealStmt = $pdo->prepare("SELECT * FROM meal_periods WHERE start_time <= ? AND end_time >= ?");
    $mealStmt->execute([$now, $now]);
    $meal = $mealStmt->fetch();

    if (!$meal) {
        $msg = "⛔ No active meal period for " . date('h:i A');
    } else {
        // 3. Find student AND verify password
        // We added "AND password = ?" to this query
        $stuStmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ? AND password = ?");
        $stuStmt->execute([$student_id, $password]);
        $student = $stuStmt->fetch();

        if (!$student) {
            // This triggers if ID is wrong OR password is wrong
            $msg = "❌ Invalid Student ID or Password";
        } else {
            // 4. Check for double entries today
            $check = $pdo->prepare("SELECT id FROM meal_logs WHERE student_id = ? AND meal_period_id = ? AND DATE(timestamp) = CURDATE()");
            $check->execute([$student['id'], $meal['id']]);

            if ($check->rowCount() > 0) {
                $msg = "⚠️ Already served {$meal['name']} today.";
            } else {
                // 5. Log the meal
                $log = $pdo->prepare("INSERT INTO meal_logs (student_id, meal_period_id, staff_id, status) VALUES (?, ?, ?, 'granted')");
                $log->execute([$student['id'], $meal['id'], $staff_id]);
                $msg = "✅ {$meal['name']} granted to " . htmlspecialchars($student['full_name']);
            }
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
        <h2>🍽 Meal Card Verification</h2>
        <form method="post">
            <input name="student_id" placeholder="Student ID" autofocus required>

            <input type="password" name="password" placeholder="Enter Password" required>

            <button>Verify</button>
        </form>
        <p><?= $msg ?></p>

    </div>

</body>

</html>