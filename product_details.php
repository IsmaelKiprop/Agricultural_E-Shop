<?php
include("connection/db.php");
include("functions.php");
$product = getProductById($_GET['id']); // Retrieve product details by ID
?>

<!-- Display product details -->
