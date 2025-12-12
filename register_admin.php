<?php
require 'config/config.php';

echo "Enter username: ";
$username = trim(fgets(STDIN));

echo "Enter password: ";
$password = trim(fgets(STDIN));
$hashed = password_hash($password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO admin_users (username, password, role) VALUES (?, ?, 'admin')");
$stmt->execute([$username, $hashed]);

echo "Admin account created successfully!\n";
?>
