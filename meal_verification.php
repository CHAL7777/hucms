<?php
require 'config/config.php';

// Staff ID (enter manually for now)
echo "Enter Staff ID: ";
$staff_id = trim(fgets(STDIN));

echo "Enter Student ID: ";
$student_id_input = trim(fgets(STDIN));

echo "Enter Meal Period Name: ";
$meal_period_name = trim(fgets(STDIN));

try {
    // Check student exists
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id=?");
    $stmt->execute([$student_id_input]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$student){
        echo "Student not found!\n";
        exit;
    }

    // Get meal period
    $stmt = $pdo->prepare("SELECT * FROM meal_periods WHERE name=?");
    $stmt->execute([$meal_period_name]);
    $meal_period = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$meal_period){
        echo "Meal period not found!\n";
        exit;
    }

    // Check if meal already served
    $stmt = $pdo->prepare("SELECT * FROM meal_logs WHERE student_id=? AND meal_period_id=?");
    $stmt->execute([$student['id'], $meal_period['id']]);
    if($stmt->rowCount() > 0){
        echo "Duplicate meal attempt! Denied.\n";
        $status = 'duplicate';
    } else {
        echo "Meal granted!\n";
        $status = 'granted';
    }

    // Insert meal log
    $stmt = $pdo->prepare("INSERT INTO meal_logs (student_id, meal_period_id, staff_id, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$student['id'], $meal_period['id'], $staff_id, $status]);

} catch(PDOException $e){
    echo "Error: " . $e->getMessage() . "\n";
}
?>
