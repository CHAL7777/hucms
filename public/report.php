<?php
session_start();
require '../config/config.php';
date_default_timezone_set('Africa/Addis_Ababa');

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];

// Query: Join students table twice (s1 = Receiver, s2 = Verifier)
$sql = "SELECT s1.student_id AS rec_id, s1.full_name AS rec_name, 
               m.name AS meal, s2.full_name AS verifier_name, 
               l.status, l.timestamp 
        FROM meal_logs l
        JOIN students s1 ON l.student_id = s1.id
        JOIN meal_periods m ON l.meal_period_id = m.id
        LEFT JOIN students s2 ON l.verified_by_student_id = s2.id";

if ($role === 'admin') {
    $stmt = $pdo->query($sql . " ORDER BY l.timestamp DESC");
    $data = $stmt->fetchAll();
} else if ($role === 'student') {
    // Students only see their own receiving history
    $stmt = $pdo->prepare($sql . " WHERE l.student_id = ? ORDER BY l.timestamp DESC");
    $stmt->execute([$_SESSION['student_db_id']]);
    $data = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meal Consumption Report</title>
    <style>
        /* Reusing your existing styles */
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; padding: 40px; }
        .container { max-width: 1100px; margin: auto; background: white; padding: 30px; border-radius: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; padding: 15px; border-bottom: 2px solid #dee2e6; background: #f8f9fa; }
        td { padding: 15px; border-bottom: 1px solid #eee; }
        .status-badge { padding: 4px 10px; border-radius: 20px; font-weight: bold; font-size: 12px; }
        .status-granted { background: #d4edda; color: #155724; }
    </style>
</head>
<body>
<div class="container">
    <h2>📊 Meal Consumption Report</h2>
    <table>
        <thead>
            <tr>
                <th>Receiver ID</th>
                <th>Student (Receiver)</th>
                <th>Meal</th>
                <th>Verified By (Student)</th>
                <th>Status</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $r): ?>
            <tr>
                <td><strong><?= htmlspecialchars($r['rec_id']) ?></strong></td>
                <td><?= htmlspecialchars($r['rec_name']) ?></td>
                <td><?= htmlspecialchars($r['meal']) ?></td>
                <td><?= htmlspecialchars($r['verifier_name'] ?? 'System') ?></td>
                <td><span class="status-badge status-<?= strtolower($r['status']) ?>"><?= $r['status'] ?></span></td>
                <td><?= date('Y-m-d h:i A', strtotime($r['timestamp'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>