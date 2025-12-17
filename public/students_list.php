<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../config/db.php";
require_once "../config/auth.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$search = $_GET['search'] ?? '';

try {
    $stmt = $pdo->prepare(
        "SELECT * FROM students 
         WHERE student_id LIKE ? OR full_name LIKE ? 
         ORDER BY created_at DESC"
    );
    $stmt->execute(["%$search%", "%$search%"]);
    $students = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records - HUCMS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="navbar">
        <div class="logo">🏛️ Student Database</div>
        <div class="nav-links">
            <a href="dashboard.php">⬅ Back to Dashboard</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        <header style="margin-bottom: 25px;">
            <h1>🎓 Registered Students</h1>
            <p style="color: #666;">View and search through all registered students in the system.</p>
        </header>

        <div class="card" style="max-width: 100%; margin-bottom: 30px; padding: 20px;">
            <form method="get" style="display: flex; gap: 10px; align-items: center;">
                <div style="flex: 1;">
                    <input type="text" name="search" placeholder="Search by Student ID or Name..."
                        value="<?= htmlspecialchars($search) ?>"
                        style="margin: 0; border: 2px solid #eee;">
                </div>
                <button type="submit" style="width: auto; padding: 12px 30px;">🔍 Search</button>
                <?php if ($search): ?>
                    <a href="students_list.php" style="text-decoration: none; color: var(--danger-red); font-size: 14px;">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="table-wrapper" style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden;">
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Full Name</th>
                        <th>Registered Date</th>
                        <th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($students) > 0): ?>
                        <?php foreach ($students as $s): ?>
                            <tr>
                                <td><code style="background: #f0f2f5; padding: 4px 8px; border-radius: 4px; color: var(--nav-dark); font-weight: bold;">
                                        <?= htmlspecialchars($s['student_id']) ?>
                                    </code></td>
                                <td style="font-weight: 500;"><?= htmlspecialchars($s['full_name']) ?></td>
                                <td style="color: #666; font-size: 0.9rem;">
                                    <?= date('M d, Y', strtotime($s['created_at'])) ?>
                                    <span style="font-size: 0.8rem; color: #999; display: block;">
                                        <?= date('h:i A', strtotime($s['created_at'])) ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span style="background: #e6fffa; color: #2c7a7b; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;">
                                        Active
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 50px; color: #999;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">🔍</div>
                                No students found matching "<strong><?= htmlspecialchars($search) ?></strong>"
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer style="text-align: center; margin-top: 40px; padding-bottom: 20px; color: #999; font-size: 0.8rem;">
        Showing <?= count($students) ?> total records
    </footer>
</body>

</html>