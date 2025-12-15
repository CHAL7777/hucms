<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
<h2>Welcome, <?= $_SESSION['username'] ?></h2>

<ul>
    <li><a href="meal_card.php">🍽 Meal Card</a></li>
    <li><a href="report.php">📊 Reports</a></li>
    <li><a href="logout.php">🚪 Logout</a></li>
</ul>
</body>
</html>
