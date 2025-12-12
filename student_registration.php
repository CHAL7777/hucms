<?php
require 'config/config.php';

echo "Enter Student ID: ";
$student_id = trim(fgets(STDIN));

echo "Enter Full Name: ";
$full_name = trim(fgets(STDIN));

echo "Enter Department: ";
$department = trim(fgets(STDIN));

echo "Enter Program: ";
$program = trim(fgets(STDIN));

echo "Enter ID Card Number: ";
$id_card = trim(fgets(STDIN));

try {
    $stmt = $pdo->prepare("INSERT INTO students (student_id, full_name, department, program, id_card) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$student_id, $full_name, $department, $program, $id_card]);
    echo "Student registered successfully!\n";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
