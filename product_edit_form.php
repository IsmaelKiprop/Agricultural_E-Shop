<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <!-- Include your CSS and JavaScript files here -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            background-image: url('images/background_3.png');
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }

        header h1 {
            margin: 0;
        }

        main {
            max-width: 1000px; /* Adjusted max-width to match the form width */
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.7); /* Transparent background */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }

        form {
            display: grid;
            gap: 10px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
        }

        select {
            appearance: none;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }
    </style>
    <script src="js/main.js"></script>
</head>
<body>
    <header>
        <h1>Edit Product</h1>
    </header>
    <main>
        <a href="admin.php">Back to Product List</a>

        <form method="POST" action="admin.php?action=edit_product&id=<?php echo $product_id; ?>" class="transparent-form" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" value="<?php echo $product['name']; ?>" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required><?php echo $product['description']; ?></textarea>

            <label for="price">Price:</label>
            <input type="text" name="price" id="price" value="<?php echo $product['price']; ?>" required>

            <label for="category_id">Category ID:</label>
            <input type="text" name="category_id" id="category_id" value="<?php echo $product['category_id']; ?>" required>

            <label for="image">Product Image:</label>
            <input type="file" name="image" id="image" accept="image/*"> <!-- Allow image file upload -->

            <!-- Add the current image as a reference -->
            <label for="current_image">Current Image:</label>
            <img src="<?php echo $product['image_url']; ?>" alt="Current Image" width="100" height="100">
            <input type="hidden" name="current_image" value="<?php echo $product['image_url']; ?>">

            <input type="submit" value="Save Changes">
        </form>
    </main>
</body>
</html>




