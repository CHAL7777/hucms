<?php
session_start();
require '../config/config.php';

if (!isset($_SESSION['staff_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = $_POST['id'];
    $name  = $_POST['name'];
    $start = $_POST['start_time'];
    $end   = $_POST['end_time'];

    $stmt = $pdo->prepare("UPDATE meal_periods SET name=?, start_time=?, end_time=? WHERE id=?");
    $stmt->execute([$name, $start, $end, $id]);
}

$meals = $pdo->query("SELECT * FROM meal_periods")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Meal Periods</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; padding: 40px; margin: 0; }
        .container { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h2 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #007bff; color: white; text-align: left; padding: 12px; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        input { padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 90%; }
        button { background-color: #28a745; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #218838; }
        .back-link { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #007bff; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="back-link">🏠 Back to Dashboard</a>
    <h2>🍽 Meal Card Periods</h2>

    <table>
    <tr>
        <th>Meal</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Action</th>
    </tr>

    <?php foreach ($meals as $m): ?>
    <tr>
        <form method="post">
            <td>
                <input type="hidden" name="id" value="<?= $m['id'] ?>">
                <input name="name" value="<?= htmlspecialchars($m['name']) ?>" required>
            </td>
            <td><input type="time" name="start_time" value="<?= $m['start_time'] ?>" required></td>
            <td><input type="time" name="end_time" value="<?= $m['end_time'] ?>" required></td>
            <td><button>Update</button></td>
        </form>
    </tr>
    <?php endforeach; ?>
    </table>
</div>

</body>
</html>