<?php
require 'config/config.php';

echo "Enter Meal Period Name (Breakfast/Lunch/Dinner): ";
$name = trim(fgets(STDIN));

echo "Enter Start Time (HH:MM:SS): ";
$start = trim(fgets(STDIN));

echo "Enter End Time (HH:MM:SS): ";
$end = trim(fgets(STDIN));

try {
    $stmt = $pdo->prepare("INSERT INTO meal_periods (name, start_time, end_time) VALUES (?, ?, ?)");
    $stmt->execute([$name, $start, $end]);
    echo "Meal period added successfully!\n";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
