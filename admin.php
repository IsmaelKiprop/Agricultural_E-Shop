<?php
// Start or resume the session
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to sanitize and validate input data
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Include authentication and database connection files
include("auth.php");
include("connection/db.php");

// Check if the user is logged in as an admin
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); // Redirect unauthorized users
    exit();
}

// Initialize $action as an empty string
$action = '';

// Process any actions (e.g., add, edit, delete products)
if (isset($_GET['action'])) {
    $action = $_GET['action']; // Store the action in a variable

    if ($action === 'add_product') {
        // Fetch the list of categories from the database (use prepared statements)
        $categories = array();
        $category_sql = "SELECT id, name FROM categories";
        $category_result = $conn->query($category_sql);
        if ($category_result->num_rows > 0) {
            while ($row = $category_result->fetch_assoc()) {
                $categories[$row['id']] = $row['name'];
            }
        }
        // Handle product addition logic here
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validate and sanitize the form data
            $name = test_input($_POST["name"]);
            $description = test_input($_POST["description"]);
            $price = test_input($_POST["price"]);
            $category_id = test_input($_POST["category_id"]);

            // Check if an image file is uploaded
            if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["image"]["name"]);

                // Check if the file already exists
                if (file_exists($target_file)) {
                    echo "Sorry, the file already exists.";
                } else {
                    // Move the uploaded file to the target directory
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        // Insert the product details into the database (use prepared statements)
                        $insert_sql = "INSERT INTO products (name, description, price, category_id, image_url) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($insert_sql);
                        $stmt->bind_param("ssdbs", $name, $description, $price, $category_id, $target_file);

                        if ($stmt->execute()) {
                            // Product successfully added
                            echo "Product successfully added.";
                        } else {
                            echo "Error adding product: " . $stmt->error;
                        }

                        $stmt->close();
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }
            } else {
                echo "Sorry, no image file was uploaded.";
            }
        }
    } elseif ($action === 'edit_product') {
        // Handle product editing logic here
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];
            // Fetch the product details based on $product_id (use prepared statements)
            $edit_sql = "SELECT * FROM products WHERE id = ?";
            $stmt = $conn->prepare($edit_sql);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $edit_result = $stmt->get_result();

            if ($edit_result->num_rows == 1) {
                $product = $edit_result->fetch_assoc();
                // Display an edit form with pre-filled values
                include("product_edit_form.php");
            } else {
                $error_message = 'Product not found.';
            }
        }
    } elseif ($action === 'delete_product') {
        // Handle product deletion logic here
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];
            // Fetch the product details based on $product_id (use prepared statements)
            $delete_sql = "SELECT * FROM products WHERE id = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $delete_result = $stmt->get_result();

            if ($delete_result->num_rows == 1) {
                $product = $delete_result->fetch_assoc();
                // Display a confirmation prompt using JavaScript
                echo '<script>';
                echo 'var confirmDelete = confirm("Are you sure you want to delete the product: ' . $product['name'] . '");';
                echo 'if (confirmDelete) {';
                echo '   window.location.href = "admin.php?action=delete_product&id=' . $product_id . '";';
                echo '}';
                echo '</script>';
            } else {
                $error_message = 'Product not found.';
            }
        }
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
    <header>
        <h1>Welcome to the Admin Dashboard</h1>
        <a href="logout.php" class="logout-button">Logout</a>
    </header>

    <nav>
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="admin.php?action=add_product">Add Product</a></li>
        </ul>
    </nav>
    <style>
        body {
            background-image: url('images/background_3.png'); /* Replace 'background.jpg' with your image path */
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }

        header h1 {
            margin: 0;
        }

        nav ul {
            background-color: #444;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
        }

        main {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.7); /* Transparent background */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        table th, table td {
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #333;
            color: #fff;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .error {
            color: #ff0000;
            font-weight: bold;
        }

        /* Add these styles for the back button */
        .back-button {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 10px;
            display: inline-block;
        }

        .back-button:hover {
            background-color: #555;
        }
        /* Add these styles for the logout button */
        .logout-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-button:hover {
            background-color: #555;
        }

        /* Style for transparent forms */
        .transparent-form {
            max-width: 400px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.7); /* Transparent background */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        /* Style for product images in the table */
        .product-image {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>
    <main>
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['action']) && $_GET['action'] !== 'add_product') : ?>
            <a class="back-button" href="admin.php">Back</a>
        <?php endif; ?>
        <?php if (isset($_GET['action']) && $_GET['action'] === 'add_product') : ?>
            <h2>Add Product</h2>
            <!-- Include the "Add Product" form here -->
            <?php include("product_add_form.php"); ?>
        <?php else : ?>
            <h2>Product List</h2>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category ID</th>
                    <th>Image</th>
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
                    echo "<td>" . $row['category_id'] . "</td>";
                    echo "<td><img src='" . $row['image_url'] . "' class='product-image'></td>";
                    echo '<td>
                            <a href="#" onclick="editProduct(' . $row['id'] . ');">Edit</a> | 
                            <a href="#" onclick="confirmDelete(' . $row['id'] . ');">Delete</a>
                        </td>';
                    echo "</tr>";

                    // Add edit and delete forms for each product
                    echo '<form id="edit-form-' . $row['id'] . '" action="admin.php?action=edit_product&id=' . $row['id'] . '" method="POST" style="display: none;">
                            <input type="hidden" name="name" value="' . $row['name'] . '">
                            <input type="hidden" name="description" value="' . $row['description'] . '">
                            <input type="hidden" name="price" value="' . $row['price'] . '">
                            <input type="hidden" name="category_id" value="' . $row['category_id'] . '">
                            <input type="hidden" name="current_image" value="' . $row['image_url'] . '">
                            <input type="submit" id="edit-submit-' . $row['id'] . '" value="Edit" style="display: none;">
                        </form>';

                    echo '<form id="delete-form-' . $row['id'] . '" action="admin.php?action=delete_product&id=' . $row['id'] . '" method="POST" style="display: none;">
                            <input type="submit" id="delete-submit-' . $row['id'] . '" value="Delete" style="display: none;">
                        </form>';
                }
                ?>
            </tbody>
        </table>
    </main>

    <footer>
        <!-- Footer content goes here -->
    </footer>
    <script>
    function editProduct(productId) {
        // Hide all edit forms and show the specific edit form
        hideAllEditForms();
        document.getElementById('edit-form-' + productId).style.display = 'block';
        document.getElementById('edit-submit-' + productId).click();
    }

    function confirmDelete(productId) {
        // Show a confirmation dialog and delete the product if confirmed
        var confirmDelete = confirm("Are you sure you want to delete this product?");
        if (confirmDelete) {
            document.getElementById('delete-submit-' + productId).click();
        }
    }

    function hideAllEditForms() {
        // Hide all edit forms
        var editForms = document.querySelectorAll('[id^="edit-form-"]');
        for (var i = 0; i < editForms.length; i++) {
            editForms[i].style.display = 'none';
        }
    }
</script>
</body>
</html>
