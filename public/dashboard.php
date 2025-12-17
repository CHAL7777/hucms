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
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f0f2f5; margin: 0; padding: 0; }
        .navbar { background: #007bff; color: white; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .container { padding: 40px; max-width: 1100px; margin: auto; }
        h2 { margin: 0; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px; }
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); text-align: center; transition: 0.3s; text-decoration: none; color: #333; display: block; border: 1px solid #e1e4e8; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-color: #007bff; }
        .card-icon { font-size: 40px; margin-bottom: 10px; display: block; }
        .card-title { font-size: 18px; font-weight: bold; color: #007bff; display: block; margin-bottom: 5px; }
        .logout-btn { background: #dc3545; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; }
        .logout-btn:hover { background: #c82333; }
    </style>
</head>
<body>

<div class="navbar">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h2>
    <a href="logout.php" class="logout-btn">🚪 Logout</a>
</div>

<div class="container">
    <div class="grid">
        <a href="meal_card.php" class="card">
            <span class="card-icon">🍽️</span>
            <span class="card-title">Meal Card Verification</span>
            <p>Scan and verify student meal access</p>
        </a>

        <a href="report.php" class="card">
            <span class="card-icon">📊</span>
            <span class="card-title">View Reports</span>
            <p>Check daily and monthly meal logs</p>
        </a>

        <a href="meal_periods_manage.php" class="card">
            <span class="card-icon">⏰</span>
            <span class="card-title">Meal Periods</span>
            <p>Manage Breakfast, Lunch, and Dinner times</p>
        </a>

        <a href="student_register.php" class="card">
            <span class="card-icon">👤</span>
            <span class="card-title">Register Student</span>
            <p>Add new students to the database</p>
        </a>

        <a href="students_list.php" class="card">
            <span class="card-icon">📋</span>
            <span class="card-title">Student List</span>
            <p>View and search registered students</p>
        </a>
    </div>
</div>

</body>
</html>