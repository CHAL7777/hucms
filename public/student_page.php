<?php
require_once "../config/auth.php";
require_once "../config/db.php";

// SECURITY: Only allow Students
if ($_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// Get the student's primary database ID from the session
$student_db_id = $_SESSION['student_db_id'];

// Fetch the student's full name and ID for the header
$stmtInfo = $pdo->prepare("SELECT full_name, student_id FROM students WHERE id = ?");
$stmtInfo->execute([$student_db_id]);
$studentInfo = $stmtInfo->fetch();

// Fetch meal history by joining meal_logs with meal_periods
$query = "SELECT l.timestamp, m.name as meal_type, l.status 
          FROM meal_logs l 
          JOIN meal_periods m ON l.meal_period_id = m.id 
          WHERE l.student_id = ? 
          ORDER BY l.timestamp DESC";
$stmtLogs = $pdo->prepare($query);
$stmtLogs->execute([$student_db_id]);
$logs = $stmtLogs->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Meal History - HUCMS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        .history-table th,
        .history-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .history-table th {
            background-color: #3498db;
            color: white;
        }

        .status-granted {
            color: #27ae60;
            font-weight: bold;
        }

        .student-info-card {
            background: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 5px solid #3498db;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="student_page.php" class="logo">HUCMS Student Portal</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="container">
        <div class="student-info-card">
            <h2>Welcome, <?= htmlspecialchars($studentInfo['full_name']) ?></h2>
            <p><strong>Student ID:</strong> <?= htmlspecialchars($studentInfo['student_id']) ?></p>
        </div>

        <h3>📅 Your Recent Meal Logs</h3>

        <?php if (count($logs) > 0): ?>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Meal Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= date('M d, Y - h:i A', strtotime($log['timestamp'])) ?></td>
                            <td><?= htmlspecialchars($log['meal_type']) ?></td>
                            <td class="status-granted"><?= ucfirst($log['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="card" style="text-align:center;">
                <p>No meal records found yet. Have you visited the cafeteria today?</p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>