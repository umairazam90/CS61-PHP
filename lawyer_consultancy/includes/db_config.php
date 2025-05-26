<?php
// includes/db_config.php

$servername = "localhost";
$username = "root";     // Default XAMPP username
$password = "";         // Default XAMPP password (empty)
$dbname = "lawyer_consultancy"; // The database name you created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Set character set to UTF-8 for proper data handling
$conn->set_charset("utf8mb4");

// Optional: Define base URL for easy navigation/redirection
define('BASE_URL', 'http://localhost/CS61-PHP/lawyer_consultancy/');
?>