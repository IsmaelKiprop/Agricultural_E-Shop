<?php
// Database configuration
include("connection/db.php");
include("functions.php");
// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email address from the form
    $email = $_POST["email"];

    // Validate the email address (you can add more robust validation)
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Insert the email address into the database
        $sql = "INSERT INTO subscribers (email) VALUES ('$email')";
        
        if ($conn->query($sql) === TRUE) {
            // Redirect to a thank you page
            header("Location: thank_you.php"); // Customize the URL
            exit;
        } else {
            // Handle the error (e.g., duplicate email)
            echo "Error: " . $conn->error;
        }
    } else {
        // Invalid email address
        echo "Invalid email address";
    }
}

// Close the database connection
$conn->close();
?>
