<?php
/* $host = "localhost";
$db   = "hucms";
$user = "root";
$pass = "1fikir2love";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Database connection failed");
} */
// Database credentials
$host = 'localhost';
$db   = 'meal_management_system'; // The name you gave your database
$user = 'root';                  // Default for XAMPP/WAMP is 'root'
$pass = '';                      // Default for XAMPP/WAMP is empty

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    
    // Set error mode to Exception to catch connection issues
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to Associative Array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // If connection fails, stop and show error
    die("Database connection failed: " . $e->getMessage());
}
?>
