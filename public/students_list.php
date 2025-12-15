<?php
session_start();
require '../config/config.php';

if (!isset($_SESSION['staff_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

$search = $_GET['search'] ?? '';

$stmt = $pdo->prepare(
    "SELECT * FROM students
     WHERE student_id LIKE ? OR full_name LIKE ?
     ORDER BY created_at DESC"
);
$stmt->execute(["%$search%", "%$search%"]);
$students = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registered Students</title>
</head>
<body>

<h2>🎓 Registered Students</h2>

<form method="get">
    <input type="text" name="search" placeholder="Search ID or name"
           value="<?= htmlspecialchars($search) ?>">
    <button>Search</button>
</form>

<table border="1" cellpadding="5">
<tr>
    <th>ID</th>
    <th>Student ID</th>
    <th>Full Name</th>
    <th>Registered At</th>
</tr>

<?php foreach ($students as $s): ?>
<tr>
    <td><?= $s['id'] ?></td>
    <td><?= $s['student_id'] ?></td>
    <td><?= $s['full_name'] ?></td>
    <td><?= $s['created_at'] ?></td>
</tr>
<?php endforeach; ?>

</table>
</body>
</html>
