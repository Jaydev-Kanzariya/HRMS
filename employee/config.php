<?php
// database.php

// Configuration for MySQL database
$host = 'localhost';
$db = 'hrms';
$user = 'root'; // Change as needed
$pass = ''; // Change as needed

// Using mysqli
$conn = mysqli_connect($host, $user, $pass, $db);

// Check mysqli connection
if (!$conn) {
    die("MySQLi Connection failed: " . mysqli_connect_error());
}

// Using PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("PDO Connection failed: " . $e->getMessage());
}

// Uncomment the following lines for debugging purposes
// echo "MySQLi Connection successful!";
// echo "PDO Connection successful!";
?>
