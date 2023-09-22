<?php
include("header.php");
include("connection/db.php"); // Include your database connection file

// Query to fetch products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

?>

<section class="shop-section">
    <div class="container">
        <h1>Shop Our Products</h1>
        <div class="row">
            <?php
            // Check if there are products in the database
            if ($result->num_rows > 0) {
                // Loop through each row (product) in the result set
                while ($row = $result->fetch_assoc()) {
            ?>
                    <div class="col-md-4">
                        <div class="product">
                            <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
                            <h2><?php echo $row['name']; ?></h2>
                            <p><?php echo $row['description']; ?></p>
                            <span class="price">$<?php echo $row['price']; ?></span>
                            <a href="cart.php" class="btn btn-primary">Add to Cart</a>
                        </div>
                    </div>
            <?php
                }
            } else {
                // If there are no products in the database
                echo "<p>No products available.</p>";
            }
            ?>
            <!-- Add more product listings as needed -->
        </div>
    </div>
</section>

<?php
include("footer.php");
?>

