<?php
$host = "localhost";
$db = "hucms";
$user = "root";
$pass = "1fikir2love";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connected successfully!\n";
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
