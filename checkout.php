<?php
include("header.php");
include("connection/db.php");

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch cart items for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT oi.id, p.name, oi.quantity, p.price
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ? AND oi.user_id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ii", $_SESSION['order_id'], $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "Error: " . $conn->error;
}

// Calculate the total price
$total_price = 0;
?>

<section class="cart-section">
    <div class="container">
        <h1>Checkout</h1>
        <h2>Review Your Order</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>$<?php echo $row['price']; ?></td>
                        <td>$<?php echo number_format($row['quantity'] * $row['price'], 2); ?></td>
                    </tr>
                    <?php $total_price += ($row['quantity'] * $row['price']); ?>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                    <td>$<?php echo number_format($total_price, 2); ?></td>
                </tr>
            </tfoot>
        </table>

        <h2>Shipping Information</h2>
        <form action="process_order.php" method="POST">
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="state">State:</label>
                <input type="text" id="state" name="state" required>
            </div>
            <div class="form-group">
                <label for="zip_code">Zip Code:</label>
                <input type="text" id="zip_code" name="zip_code" required>
            </div>
            <div class="form-group">
                <label for="country">Country:</label>
                <input type="text" id="country" name="country" required>
            </div>
            <input type="submit" value="Place Order" class="btn btn-primary">
        </form>
    </div>
</section>

<?php
include("footer.php");
?>
