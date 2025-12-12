<?php
require 'config/config.php';

echo "Enter username: ";
$username = trim(fgets(STDIN));

echo "Enter password: ";
$password = trim(fgets(STDIN));

$stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username=?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    echo "Login successful! Role: " . $user['role'] . "\n";
} else {
    echo "Invalid credentials!\n";
}
?>
