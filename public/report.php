<?php
session_start();
require '../config/config.php';

if (!isset($_SESSION['staff_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

$stmt = $pdo->query(
    "SELECT s.student_id, s.full_name, m.name meal,
            u.username staff, l.status, l.timestamp
     FROM meal_logs l
     JOIN students s ON l.student_id=s.id
     JOIN meal_periods m ON l.meal_period_id=m.id
     JOIN admin_users u ON l.staff_id=u.id
     ORDER BY l.timestamp DESC"
);
$data = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Reports</title></head>
<body>
<h2>📊 Meal Reports</h2>
<table border="1">
<tr>
<th>Student</th><th>Name</th><th>Meal</th>
<th>Staff</th><th>Status</th><th>Time</th>
</tr>
<?php foreach($data as $r): ?>
<tr>
<td><?= $r['student_id'] ?></td>
<td><?= $r['full_name'] ?></td>
<td><?= $r['meal'] ?></td>
<td><?= $r['staff'] ?></td>
<td><?= $r['status'] ?></td>
<td><?= $r['timestamp'] ?></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
