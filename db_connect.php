<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // Default for XAMPP is no password
$db   = 'library_db';

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}


?>
