<!-- product_add_form.php -->
<form method="POST" action="admin.php?action=add_product" enctype="multipart/form-data" class="transparent-form">
    <label for="name">Product Name:</label>
    <input type="text" name="name" id="name" required>

    <label for="description">Description:</label>
    <textarea name="description" id="description" required></textarea>

    <label for="price">Price:</label>
    <input type="text" name="price" id="price" required>

    <label for="category_id">Category:</label>
    <select name="category_id" id="category_id" required>
        <option value="" disabled selected>Select a category</option>
        <?php
        foreach ($categories as $id => $categoryName) {
            echo '<option value="' . $id . '">' . $categoryName . '</option>';
        }
        ?>
    </select>

    <label for="image">Product Image:</label>
    <input type="file" name="image" id="image" accept="image/*" required>

    <input type="submit" value="Add Product">
</form>

<style>
    .transparent-form {
        max-width: 400px;
        margin: 0 auto;
        background-color: rgba(255, 255, 255, 0.7); /* Transparent background */
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .transparent-form label {
        font-weight: bold;
    }

    .transparent-form input[type="text"],
    .transparent-form select,
    .transparent-form textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-top: 5px;
    }

    .transparent-form select {
        appearance: none;
    }

    .transparent-form input[type="file"] {
        border: none;
        padding: 5px;
    }

    .transparent-form input[type="submit"] {
        background-color: #333;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
    }

    .transparent-form input[type="submit"]:hover {
        background-color: #555;
    }
</style>

