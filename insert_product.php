<?php
include("connection/db.php"); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $image_url = $_POST["image_url"];
    $category_id = $_POST["category_id"];

    // Insert data into the products table
    $sql = "INSERT INTO products (name, description, price, image_url, category_id)
            VALUES ('$name', '$description', $price, '$image_url', $category_id)";

    if ($conn->query($sql) === TRUE) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
