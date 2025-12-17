<?php
session_start();
require '../config/config.php';
date_default_timezone_set('Africa/Addis_Ababa');

// Remove staff check if this is a public terminal, 
// otherwise keep it for security
if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit;
}

$msg = "";
$status_class = "";
$staff_id = $_SESSION['staff_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id']);
    $now = date('H:i:s');
    $today = date('Y-m-d');

    // 1. VERIFY REGISTRATION (Is the student in the system?)
    $stuStmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stuStmt->execute([$student_id]);
    $student = $stuStmt->fetch();

    if (!$student) {
        $msg = "❌ Student not found. Please register first at the office.";
        $status_class = "error";
    } else {
        // 2. VERIFY MEAL PERIOD (Is it actually meal time?)
        $mealStmt = $pdo->prepare("SELECT * FROM meal_periods WHERE start_time <= ? AND end_time >= ?");
        $mealStmt->execute([$now, $now]);
        $meal = $mealStmt->fetch();

        if (!$meal) {
            $msg = "⏳ No active meal period. Current Time: " . date('h:i A');
            $status_class = "warning";
        } else {
            // 3. VERIFY UNIQUENESS (Has the student already eaten THIS meal today?)
            $check = $pdo->prepare("
                SELECT id FROM meal_logs 
                WHERE student_id = ? 
                AND meal_period_id = ? 
                AND DATE(timestamp) = ? 
                AND status = 'granted'
            ");
            $check->execute([$student['id'], $meal['id'], $today]);

            if ($check->rowCount() > 0) {
                // IGNORE: Attempt is ignored, no new database entry made
                $msg = "🚫 ACCESS DENIED: Student already received " . $meal['name'] . " today.";
                $status_class = "error";
            } else {
                // SUCCESS: Record the entry and grant access
                $insert = $pdo->prepare("
                    INSERT INTO meal_logs (student_id, meal_period_id, staff_id, status) 
                    VALUES (?, ?, ?, 'granted')
                ");
                $insert->execute([$student['id'], $meal['id'], $staff_id]);
                
                $msg = "✅ Welcome, " . $student['full_name'] . "! " . $meal['name'] . " is granted.";
                $status_class = "success";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meal Verification Terminal</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: #2c3e50; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .terminal { 
            background: white; 
            padding: 50px; 
            border-radius: 25px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.4); 
            width: 100%; 
            max-width: 480px; 
            text-align: center; 
        }
        .icon { font-size: 60px; margin-bottom: 15px; }
        h2 { color: #2c3e50; margin-bottom: 10px; font-size: 28px; }
        p { color: #7f8c8d; margin-bottom: 30px; }

        input { 
            width: 100%; 
            padding: 20px; 
            font-size: 22px; 
            border: 3px solid #dfe6e9; 
            border-radius: 12px; 
            margin-bottom: 25px; 
            text-align: center; 
            box-sizing: border-box; 
            outline: none;
            transition: border-color 0.3s;
        }
        input:focus { border-color: #3498db; }

        button { 
            width: 100%; 
            padding: 18px; 
            background: #3498db; 
            color: white; 
            border: none; 
            border-radius: 12px; 
            font-size: 20px; 
            cursor: pointer; 
            font-weight: bold; 
            transition: background 0.3s;
        }
        button:hover { background: #2980b9; }

        .msg { 
            margin-top: 30px; 
            padding: 20px; 
            border-radius: 12px; 
            font-weight: bold; 
            font-size: 18px;
        }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    </style>
</head>
<body>

<div class="terminal">
    <div class="icon">🍽️</div>
    <h2>Verification Portal</h2>
    <p>Scanner Active. Please present Student ID.</p>
    
    <form method="post">
        <input type="text" name="student_id" placeholder="Scan or Type ID..." autofocus required autocomplete="off">
        <button type="submit">Verify & Grant Access</button>
        <a href="dashboard.php" style="text-decoration: none; color: #007bff;">🏠 Back to Dashboard</a>
    </form>

    <?php if($msg): ?>
        <div class="msg <?= $status_class ?>">
            <?= $msg ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>