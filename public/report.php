<?php
session_start();
require '../config/config.php';

if (!isset($_SESSION['staff_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

$stmt = $pdo->query(
    "SELECT s.student_id, s.full_name, m.name meal,
            u.username staff, l.status, l.timestamp
     FROM meal_logs l
     JOIN students s ON l.student_id=s.id
     JOIN meal_periods m ON l.meal_period_id=m.id
     JOIN admin_users u ON l.staff_id=u.id
     ORDER BY l.timestamp DESC"
);
$data = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports | Cafe Manager</title>
    <style>
        /* Base styles */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f4f7f6; 
            margin: 0; 
            padding: 40px; 
        }

        .container { 
            max-width: 1100px; 
            margin: auto; 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        h2 { 
            color: #333; 
            margin: 0;
        }

        /* Navigation Button */
        .back-btn { 
            text-decoration: none; 
            color: white; 
            background-color: #007bff;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            transition: background 0.3s;
        }

        .back-btn:hover { 
            background-color: #0056b3; 
        }

        /* Table Styles */
        table { 
            width: 100%; 
            border-collapse: collapse; 
        }

        th { 
            background-color: #f8f9fa; 
            color: #333; 
            text-align: left; 
            padding: 15px; 
            font-size: 13px;
            text-transform: uppercase;
            border-bottom: 2px solid #dee2e6;
        }

        td { 
            padding: 15px; 
            border-bottom: 1px solid #eee; 
            color: #555;
            font-size: 15px;
        }

        tr:hover { 
            background-color: #fcfdff; 
        }

        /* Status Badge Styling */
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: capitalize;
        }

        .status-granted { 
            background-color: #d4edda; 
            color: #155724; 
        }

        .status-duplicate { 
            background-color: #fff3cd; 
            color: #856404; 
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h2>📊 Meal Consumption Reports</h2>
        <a href="dashboard.php" class="back-btn">🔙 Back to Dashboard</a>
    </header>

    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Full Name</th>
                <th>Meal Type</th>
                <th>Staff Member</th>
                <th>Access Status</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $r): ?>
            <tr>
                <td><strong><?= htmlspecialchars($r['student_id']) ?></strong></td>
                <td><?= htmlspecialchars($r['full_name']) ?></td>
                <td><?= htmlspecialchars($r['meal']) ?></td>
                <td><?= htmlspecialchars($r['staff']) ?></td>
                <td>
                    <span class="status-badge status-<?= $r['status'] ?>">
                        <?= htmlspecialchars($r['status']) ?>
                    </span>
                </td>
                <td><?= date('Y-m-d h:i A', strtotime($r['timestamp'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>