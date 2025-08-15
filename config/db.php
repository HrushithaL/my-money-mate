<?php
$servername = "localhost:3307";
$username = "root";
$password = ""; // If you set a password, add it here.
$dbname = "mymoneymate";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
