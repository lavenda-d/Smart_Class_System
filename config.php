<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$db_server = "localhost";
$db_user = "root"; // Your MySQL username
$db_pass = ""; // Your MySQL password
$db_name = "school"; // The name of your database

// Create PDO connection
try {
    $pdo = new PDO("mysql:host=$db_server;dbname=$db_name", $db_user, $db_pass);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully<br>";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Debugging: Check if $pdo is defined
if (isset($pdo)) {
    echo "PDO connection is set.<br>";
} else {
    echo "PDO connection is NOT set.<br>";
}
?>