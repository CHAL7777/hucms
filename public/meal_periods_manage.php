<?php
require_once "../config/auth.php";
require_once "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

$msg = "";

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id    = $_POST['id'];
    $name  = $_POST['name'];
    $start = $_POST['start_time'];
    $end   = $_POST['end_time'];

    $stmt = $pdo->prepare("UPDATE meal_periods SET name=?, start_time=?, end_time=? WHERE id=?");
    $stmt->execute([$name, $start, $end, $id]);
    $msg = "✅ Updated successfully";
}

// Fetch periods
$meals = $pdo->query("SELECT * FROM meal_periods")->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Manage Meal Periods</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="navbar">
        <a href="dashboard.php">Dashboard</a>
    </div>

    <div class="container">
        <h2>🍽 Manage Meal Periods</h2>
        <p><?= $msg ?></p>

        <table border="1" cellpadding="10" style="width:100%; border-collapse: collapse; background: white;">
            <tr style="background: #eee;">
                <th>Meal Name</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Action</th>
            </tr>

            <?php if (empty($meals)): ?>
                <tr>
                    <td colspan="4">No periods found. Please run the SQL setup.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($meals as $m): ?>
                    <tr>
                        <form method="post">
                            <td>
                                <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                <input type="text" name="name" value="<?= htmlspecialchars($m['name'] ?? '') ?>" required>
                            </td>
                            <td>
                                <input type="time" name="start_time" value="<?= $m['start_time'] ?? '00:00' ?>" required>
                            </td>
                            <td>
                                <input type="time" name="end_time" value="<?= $m['end_time'] ?? '00:00' ?>" required>
                            </td>
                            <td>
                                <button type="submit">Save</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</body>

</html>