<?php
// db_connection.php
$servername = "your_servername";
$username = "your_username";
$password = "your_password";
$dbname = "safeplace";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
