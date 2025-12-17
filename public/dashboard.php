<?php
include "../config/auth.php";
include "../config/db.php";
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Dashboard</title>
</head>

<body>

    <div class="navbar">
        <h2>HUCMS Dashboard</h2>
        <a href="students_list.php">Students</a>

        <a href="report.php">Reports</a>
        <a href="logout.php" class="logout">Logout</a>
        <a href="meal_periods_manage.php">Meal Periods</a>
        <a href="meal_card.php" class="nav-item active">🍽️ Meal Verification</a>
        <a href="student_register.php">📝 Register</a>

    </div>

    <div class="container">
        <h3>Welcome, <?= $_SESSION['user']; ?> 👋</h3>
        <p>System is running successfully.</p>
    </div>

</body>

</html>