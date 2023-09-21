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
        <h1>Your Shopping Cart</h1>
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

        <!-- Proceed to Checkout Button -->
        <form action="checkout.php" method="POST">
            <input type="submit" value="Proceed to Checkout">
        </form>
    </div>
</section>

<?php
include("footer.php");
?>




