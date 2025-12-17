<?php
require_once "../config/auth.php";
require_once "../config/db.php";

// SECURITY: Only allow Admins
if ($_SESSION['role'] !== 'admin') {
    header("Location: staff_portal.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - HUCMS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="navbar">
        <div class="logo">🏛️ HUCMS ADMIN</div>
        <div class="nav-links">
            <span>Welcome, <strong><?= htmlspecialchars($_SESSION['user']) ?></strong></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        <header style="margin-bottom: 30px;">
            <h1>🛠️ System Management</h1>
            <p style="color: #666;">Control student registration, monitor reports, and manage meal timings.</p>
        </header>

        <div class="menu-grid">

            <a href="student_register.php" class="menu-card">
                <div style="font-size: 2rem; margin-bottom: 10px;">📝</div>
                <h3>Register Student</h3>
                <p style="font-size: 0.8rem; color: #777;">Add new students to the system</p>
            </a>

            <a href="students_list.php" class="menu-card">
                <div style="font-size: 2rem; margin-bottom: 10px;">📋</div>
                <h3>Student List</h3>
                <p style="font-size: 0.8rem; color: #777;">View and manage student records</p>
            </a>

            <a href="meal_periods_manage.php" class="menu-card">
                <div style="font-size: 2rem; margin-bottom: 10px;">⏰</div>
                <h3>Meal Times</h3>
                <p style="font-size: 0.8rem; color: #777;">Setup breakfast, lunch, and dinner</p>
            </a>

            <a href="report.php" class="menu-card">
                <div style="font-size: 2rem; margin-bottom: 10px;">📊</div>
                <h3>Meal Reports</h3>
                <p style="font-size: 0.8rem; color: #777;">View history and analytics</p>
            </a>

            <a href="meal_card.php" class="menu-card" style="grid-column: 1 / -1; border-bottom-color: var(--success-green); background: #f0fff4;">
                <div style="font-size: 2rem; margin-bottom: 10px;">🍽️</div>
                <h3 style="color: var(--nav-dark);">Open Meal Verification</h3>
                <p style="color: var(--accent-blue);">Launch the scanner/entry screen for the cafeteria</p>
            </a>

        </div>
    </div>

    <footer style="text-align: center; margin-top: 50px; color: #999; font-size: 0.8rem;">
        &copy; <?= date('Y') ?> HUCMS - Cafeteria Management System
    </footer>
</body>

</html>