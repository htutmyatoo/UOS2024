<?php
define( 'DOMAIN', 'http://localhost:8080/uos2024/public/');

$serverName = "localhost";
$databaseName = "uos2024";
$username = "root";
$password = "";

// PDO connection attempt
try {
    $conn = new PDO("mysql:host=$serverName;dbname=$databaseName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error handling
    // echo "Connection successful";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Close connection when finished
// if ($conn) {
//     $conn = null;
// }
?>