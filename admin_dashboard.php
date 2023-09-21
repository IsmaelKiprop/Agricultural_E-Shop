<?php
// Include authentication and database connection files
include("auth.php");
include("connection/db.php");

// Check if the user is logged in as an admin
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); // Redirect unauthorized users
    exit();
}

// Process any actions (e.g., add, edit, delete products)
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'add_product') {
        // Handle product addition logic here
        // You can include a form to add products or use AJAX for product addition
    } elseif ($_GET['action'] === 'edit_product') {
        // Handle product editing logic here
    } elseif ($_GET['action'] === 'delete_product') {
        // Handle product deletion logic here
    }
}

// Query to fetch the list of products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <!-- Include your CSS and JavaScript files here -->
    <link rel="stylesheet" type="text/css" href="admin.css">
    <script src="admin.js"></script>
</head>
<body>
    <header>
        <h1>Welcome to the Admin Dashboard</h1>
        <a href="logout.php">Logout</a>
    </header>

    <nav>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="admin_dashboard.php?action=add_product">Add Product</a></li>
        </ul>
    </nav>

    <main>
        <h2>Product List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display the list of products
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td>$" . $row['price'] . "</td>";
                    echo '<td><a href="admin_dashboard.php?action=edit_product&id=' . $row['id'] . '">Edit</a> | <a href="admin_dashboard.php?action=delete_product&id=' . $row['id'] . '">Delete</a></td>';
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <footer>
        <!-- Footer content goes here -->
    </footer>
</body>
</html>

