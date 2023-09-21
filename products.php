<?php
include("connection/db.php");
include("functions.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <h1>Products</h1>
    </header>
    <nav>
        <!-- Navigation menu -->
    </nav>
    <main>
        <?php
        $products = getProducts(); // Retrieve products from the database
        foreach ($products as $product) {
            // Display product information
        }
        ?>
    </main>
    <footer>
        &copy; <?php echo date("Y"); ?> Agricultural e-Shop
    </footer>
</body>
</html>
