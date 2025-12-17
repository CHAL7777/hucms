<?php
require_once "../config/auth.php";
require_once "../config/db.php";

if ($_SESSION['role'] !== 'admin') {
    header("Location: staff_portal.php");
    exit();
}

// Fetch all meal logs with joined data for names
$query = "SELECT l.timestamp, s.student_id, s.full_name, m.name as meal_type, u.username as verified_by 
          FROM meal_logs l
          JOIN students s ON l.student_id = s.id
          JOIN meal_periods m ON l.meal_period_id = m.id
          JOIN users u ON l.staff_id = u.id
          ORDER BY l.timestamp DESC";
$logs = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Meal Reports</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="navbar">
        <a href="dashboard.php">Back to Dashboard</a>
        <button onclick="window.print()">🖨️ Print Report</button>
    </div>

    <div class="container">
        <h1>📊 Detailed Meal Log Report</h1>
        <table border="1" style="width:100%; border-collapse: collapse; margin-top: 20px;">
            <thead style="background: #f4f4f4;">
                <tr>
                    <th>Date & Time</th>
                    <th>Student ID</th>
                    <th>Full Name</th>
                    <th>Meal Type</th>
                    <th>Verified By (Staff)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($logs) > 0): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= date('Y-m-d H:i', strtotime($log['timestamp'])) ?></td>
                            <td><?= htmlspecialchars($log['student_id']) ?></td>
                            <td><?= htmlspecialchars($log['full_name']) ?></td>
                            <td><?= htmlspecialchars($log['meal_type']) ?></td>
                            <td><?= htmlspecialchars($log['verified_by']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">No records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>