<?php
require_once "../config/auth.php";
require_once "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied. Admin privileges required.");
}

// 1. Define the query string clearly
$query = "SELECT s.student_id, s.full_name, m.name AS meal, 
                 u.username AS staff, l.status, l.timestamp 
          FROM meal_logs l
          JOIN students s ON l.student_id = s.id
          JOIN meal_periods m ON l.meal_period_id = m.id
          JOIN users u ON l.staff_id = u.id 
          ORDER BY l.timestamp DESC";

// 2. Pass the defined $query variable into the PDO method
try {
    $stmt = $pdo->query($query);
    $data = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Report Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Reports</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="navbar">
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
    <div class="container">
        <h2>📊 Meal Consumption Report</h2>
        <table>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Meal Type</th>
                <th>Verified By</th>
                <th>Status</th>
                <th>Time</th>
            </tr>
            <?php foreach ($data as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['student_id']) ?></td>
                    <td><?= htmlspecialchars($r['full_name']) ?></td>
                    <td><?= htmlspecialchars($r['meal']) ?></td>
                    <td><?= htmlspecialchars($r['staff']) ?></td>
                    <td><?= htmlspecialchars($r['status']) ?></td>
                    <td><?= $r['timestamp'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>