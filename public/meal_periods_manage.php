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

    $stmt = $pdo->prepare(
        "UPDATE meal_periods
         SET name=?, start_time=?, end_time=?
         WHERE id=?"
    );
    $stmt->execute([$name, $start, $end, $id]);
}

$meals = $pdo->query("SELECT * FROM meal_periods")->fetchAll();
?>php
<!DOCTYPE html>
<html>
<head>
    <title>Meal Periods</title>
</head>
<body>

<h2>🍽 Meal Card Periods</h2>

<table border="1" cellpadding="5">
<tr>
    <th>Meal</th>
    <th>Start Time</th>
    <th>End Time</th>
    <th>Update</th>
</tr>

<?php foreach ($meals as $m): ?>
<tr>
<form method="post">
    <td>
        <input type="hidden" name="id" value="<?= $m['id'] ?>">
        <input name="name" value="<?= $m['name'] ?>" required>
    </td>
    <td><input type="time" name="start_time" value="<?= $m['start_time'] ?>" required></td>
    <td><input type="time" name="end_time" value="<?= $m['end_time'] ?>" required></td>
    <td><button>Save</button></td>
</form>
</tr>
<?php endforeach; ?>

</table>
</body>
</html>
