<?php

$servername = "localhost";
$username = "root";  
$password = "";        
$dbname = "lawyer_consultancy"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

define('BASE_URL', 'http://localhost/CS61-PHP/lawyer_consultancy/');
?>