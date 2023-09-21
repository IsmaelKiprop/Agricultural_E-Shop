<?php
// Function to sanitize and validate input data
function test_input($data) {
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

// Process any actions (e.g., add, edit, delete products)
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'add_product') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize user inputs
            $name = test_input($_POST["name"]);
            $description = test_input($_POST["description"]);
            $price = test_input($_POST["price"]);
            $category_id = test_input($_POST["category_id"]);

            // Handle image upload
            $targetDirectory = "uploads/";
            $targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if the image file is an actual image or fake image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                // It's a valid image
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
                echo "File is not an image.";
            }

            // Check file size (adjust this as needed)
            if ($_FILES["image"]["size"] > 5000000) { // 5 MB
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain image file formats (you can customize this list)
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            if ($uploadOk === 1) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                    // Image uploaded successfully
                    $image_url = $targetFile;

                    // Insert the new product into the database with the image URL
                    $insert_sql = "INSERT INTO products (name, description, price, image_url, category_id) VALUES ('$name', '$description', $price, '$image_url', $category_id)";
                    if ($conn->query($insert_sql) === TRUE) {
                        // Product added successfully, you can redirect or display a success message
                        header("Location: admin.php");
                        exit();
                    } else {
                        // Product addition failed, handle the error
                        echo "Error: " . $conn->error;
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }

        // Fetch the list of categories from the database
        $categories = array();
        $category_sql = "SELECT id, name FROM categories";
        $category_result = $conn->query($category_sql);
        if ($category_result->num_rows > 0) {
            while ($row = $category_result->fetch_assoc()) {
                $categories[$row['id']] = $row['name'];
            }
        }

        // Display the form for adding products
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Add Product</title>
            <!-- Include your CSS and JavaScript files here -->
            <link rel="stylesheet" type="text/css" href="admin.css">
            <script src="admin.js"></script>
        </head>
        <body>
            <header>
                <h1>Add Product</h1>
            </header>
            <main>
            <form method="POST" action="admin.php?action=add_product" enctype="multipart/form-data">
                <label for="name">Product Name:</label>
                <input type="text" name="name" id="name" required><br>

                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea><br>

                <label for="price">Price:</label>
                <input type="text" name="price" id="price" required><br>

                <label for="category_id">Category:</label>
                <select name="category_id" id="category_id" required>
                    <option value="" disabled selected>Select a category</option>
                    <option value="1">Fruits</option>
                    <option value="2">Vegetables</option>
                    <option value="3">Juices</option>
                    <option value="4">Dried and Cereals</option>
                    <!-- Add more predefined options as needed -->
                </select><br>


                <label for="image">Product Image:</label>
                <input type="file" name="image" id="image" accept="image/*" required><br>

                <input type="submit" value="Add Product">
            </form>
            </main>
            </body>
            </html>
        <?php
    } elseif ($_GET['action'] === 'edit_product') {
        // Handle product editing logic here
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];
            // Fetch the product details based on $product_id
            $edit_sql = "SELECT * FROM products WHERE id = $product_id";
            $edit_result = $conn->query($edit_sql);

            if ($edit_result->num_rows == 1) {
                $product = $edit_result->fetch_assoc();
                // Display an edit form with pre-filled values
                echo '<h2>Edit Product</h2>';
                echo '<form method="POST" action="admin.php?action=edit_product&id=' . $product_id . '">';
                echo '<label for="name">Product Name:</label>';
                echo '<input type="text" name="name" id="name" value="' . $product['name'] . '"><br>';
                echo '<label for="description">Description:</label>';
                echo '<textarea name="description" id="description">' . $product['description'] . '</textarea><br>';
                echo '<label for="price">Price:</label>';
                echo '<input type="text" name="price" id="price" value="' . $product['price'] . '"><br>';
                echo '<label for="category_id">Category ID:</label>';
                echo '<input type="text" name="category_id" id="category_id" value="' . $product['category_id'] . '"><br>';
                // Add image input or URL input based on your preference
                // ...
                echo '<input type="submit" value="Save Changes">';
                echo '</form>';
            } else {
                echo 'Product not found.';
            }
        }
    } elseif ($_GET['action'] === 'delete_product') {
        // Handle product deletion logic here
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];
            // Fetch the product details based on $product_id
            $delete_sql = "SELECT * FROM products WHERE id = $product_id";
            $delete_result = $conn->query($delete_sql);

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
                echo 'Product not found.';
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
    <!-- Include your CSS and JavaScript files here -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="js/main.js"></script>
</head>
<body>
    <header>
        <h1>Welcome to the Admin Dashboard</h1>
        <a href="logout.php">Logout</a>
    </header>

    <nav>
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="admin.php?action=add_product">Add Product</a></li>
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
                    <th>Category ID</th>
                    <!-- Include Image URL column here if needed -->
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
                    // Include Image URL column here if needed
                    echo '<td><a href="admin.php?action=edit_product&id=' . $row['id'] . '">Edit</a> | <a href="admin.php?action=delete_product&id=' . $row['id'] . '">Delete</a></td>';
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
