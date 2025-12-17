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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registered Students</title>
    <style>
        /* Base styles */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f4f7f6; 
            margin: 0; 
            padding: 40px; 
        }

        .container { 
            max-width: 1000px; 
            margin: auto; 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
        }

        h2 { 
            color: #333; 
            margin-top: 0; 
            border-bottom: 3px solid #007bff; 
            padding-bottom: 10px; 
            display: inline-block;
        }

        /* Navigation */
        .back-link { 
            display: block; 
            margin-bottom: 20px; 
            text-decoration: none; 
            color: #007bff; 
            font-weight: bold; 
        }

        .back-link:hover { text-decoration: underline; }

        /* Search Form */
        .search-container {
            margin-bottom: 25px;
            display: flex;
            gap: 10px;
        }

        input[type="text"] {
            flex-grow: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .search-btn {
            padding: 10px 25px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        .search-btn:hover { background-color: #0056b3; }

        /* Table Styles */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }

        th { 
            background-color: #007bff; 
            color: white; 
            text-align: left; 
            padding: 15px; 
            font-size: 14px;
            text-transform: uppercase;
        }

        td { 
            padding: 15px; 
            border-bottom: 1px solid #eee; 
            color: #555;
            font-size: 15px;
        }

        tr:last-child td { border-bottom: none; }

        tr:hover { background-color: #f8fbff; }

        /* Empty state */
        .no-data {
            text-align: center;
            padding: 30px;
            color: #888;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="back-link">🏠 Back to Dashboard</a>
    
    <h2>🎓 Registered Students</h2>

    <form method="get" class="search-container">
        <input type="text" name="search" placeholder="Search by Student ID or Full Name..."
               value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="search-btn">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Student ID</th>
                <th>Full Name</th>
                <th>Registered At</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($students) > 0): ?>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['id']) ?></td>
                    <td><strong><?= htmlspecialchars($s['student_id']) ?></strong></td>
                    <td><?= htmlspecialchars($s['full_name']) ?></td>
                    <td><?= htmlspecialchars($s['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="no-data">No students found matching your search.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>