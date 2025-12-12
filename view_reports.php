<?php
require 'config/config.php';

echo "Choose report type:\n1. Daily\n2. Weekly\n3. Student History\n";
$type = trim(fgets(STDIN));

try {
    switch($type){
        case '1': // Daily
            $stmt = $pdo->prepare("SELECT s.student_id, s.full_name, m.name as meal_period, l.status, l.timestamp 
                                   FROM meal_logs l
                                   JOIN students s ON l.student_id = s.id
                                   JOIN meal_periods m ON l.meal_period_id = m.id
                                   WHERE DATE(l.timestamp) = CURDATE()");
            $stmt->execute();
            break;
        case '2': // Weekly
            $stmt = $pdo->prepare("SELECT s.student_id, s.full_name, m.name as meal_period, l.status, l.timestamp 
                                   FROM meal_logs l
                                   JOIN students s ON l.student_id = s.id
                                   JOIN meal_periods m ON l.meal_period_id = m.id
                                   WHERE l.timestamp >= CURDATE() - INTERVAL 7 DAY");
            $stmt->execute();
            break;
        case '3': // Student History
            echo "Enter Student ID: ";
            $sid = trim(fgets(STDIN));
            $stmt = $pdo->prepare("SELECT s.student_id, s.full_name, m.name as meal_period, l.status, l.timestamp 
                                   FROM meal_logs l
                                   JOIN students s ON l.student_id = s.id
                                   JOIN meal_periods m ON l.meal_period_id = m.id
                                   WHERE s.student_id = ?");
            $stmt->execute([$sid]);
            break;
        default:
            echo "Invalid option.\n";
            exit;
    }

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as $row){
        echo $row['student_id'] . " | " . $row['full_name'] . " | " . $row['meal_period'] . " | " . $row['status'] . " | " . $row['timestamp'] . "\n";
    }

} catch(PDOException $e){
    echo "Error: " . $e->getMessage() . "\n";
}
?>
