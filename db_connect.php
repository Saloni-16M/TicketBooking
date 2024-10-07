<?php
// db_connect.php
$host = "localhost";
$user = "root";   // Database username
$password = "";   // Database password
$db = "event_db"; // Database name
$port=3307;

// Create a connection to MySQL
$conn = new mysqli($host, $user, $password, $db,$port);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
