<?php
// Ensure session is started safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../config/db.php";   // Provides $pdo
require_once "../config/auth.php"; // Ensures user is logged in

// Check permissions
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied. Admin privileges required.");
}

$search = $_GET['search'] ?? '';

try {
    // Using the $pdo object
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
    <title>Registered Students</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Modern Responsive Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
        }

        .container {
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }

        .search-box {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .search-box input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-box button {
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Table Responsiveness */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        @media (max-width: 600px) {
            .search-box {
                flex-direction: column;
            }

            h2 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="dashboard.php" style="text-decoration: none; color: #333; font-weight: bold;">⬅ Back to Dashboard</a>
    </div>

    <div class="container">
        <h2>🎓 Registered Students</h2>

        <form method="get" class="search-box">
            <input type="text" name="search" placeholder="Search by ID or Name..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Full Name</th>
                        <th>Registered At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($students) > 0): ?>
                        <?php foreach ($students as $s): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($s['student_id']) ?></strong></td>
                                <td><?= htmlspecialchars($s['full_name']) ?></td>
                                <td><?= date('Y-m-d H:i', strtotime($s['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">No students found matching "<?= htmlspecialchars($search) ?>"</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>