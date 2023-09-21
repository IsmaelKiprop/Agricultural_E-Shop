<?php
$hostname = "localhost"; // Change to "localhost" if your database server is on the same machine
$username = "root"; // Change to your XAMPP MySQL username (default is "root")
$password = ""; // By default, XAMPP has no password (leave it empty)
$database = "agricultural e-shop"; // Change to the name of your database

// Create a connection to the database
$conn = new mysqli($hostname, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If the connection is successful, you can perform database operations here.
// ...
?>