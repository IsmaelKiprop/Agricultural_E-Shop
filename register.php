<?php
// Include your database connection file
include("connection/db.php");

// Define variables to store user input and error messages
$username = $email = $password = $first_name = $last_name = $address = $city = $state = $zip_code = $country = $phone_number = $role = "";
$username_err = $email_err = $password_err = $role_err = "";

// Check if the registration form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize user inputs
    $username = test_input($_POST["username"]);
    $email = test_input($_POST["email"]);
    $password = test_input($_POST["password"]);
    $first_name = test_input($_POST["first_name"]);
    $last_name = test_input($_POST["last_name"]);
    $address = test_input($_POST["address"]);
    $city = test_input($_POST["city"]);
    $state = test_input($_POST["state"]);
    $zip_code = test_input($_POST["zip_code"]);
    $country = test_input($_POST["country"]);
    $phone_number = test_input($_POST["phone_number"]);
    $role = test_input($_POST["role"]);

    // Check if username is empty
    if (empty($username)) {
        $username_err = "Please enter a username.";
    } else {
        // Check if the username already exists in the database
        $sql = "SELECT id FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $username_err = "Username already exists. Please choose a different username.";
        }
    }

    // Check if email is empty and valid
    if (empty($email)) {
        $email_err = "Please enter an email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        // Check if the email already exists in the database
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $email_err = "Email already exists. Please use a different email.";
        }
    }

    // Check if password is empty
    if (empty($password)) {
        $password_err = "Please enter a password.";
    }

    // Check if role is empty (You can add more validation as needed)
    if (empty($role)) {
        $role_err = "Please select a role.";
    }

    // If there are no validation errors, insert user data into the database
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($role_err)) {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the database
        $sql = "INSERT INTO users (username, email, password, first_name, last_name, address, city, state, zip_code, country, phone_number, role)
                VALUES ('$username', '$email', '$hashed_password', '$first_name', '$last_name', '$address', '$city', '$state', '$zip_code', '$country', '$phone_number', '$role')";

        if ($conn->query($sql) === TRUE) {
            // Redirect to the login page or a confirmation page
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close the database connection
    $conn->close();
}

// Function to sanitize user inputs
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <!-- Include your CSS styles here -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Registration Form -->
    <h2>Registration</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <!-- Username -->
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="<?php echo $username; ?>">
        <span class="error"><?php echo $username_err; ?></span><br>

        <!-- Email -->
        <label for="email">Email:</label>
        <input type="text" name="email" id="email" value="<?php echo $email; ?>">
        <span class="error"><?php echo $email_err; ?></span><br>

        <!-- Password -->
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" value="<?php echo $password; ?>">
        <span class="error"><?php echo $password_err; ?></span><br>

        <!-- First Name -->
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" id="first_name" value="<?php echo $first_name; ?>"><br>

        <!-- Last Name -->
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>"><br>

        <!-- Address -->
        <label for="address">Address:</label>
        <input type="text" name="address" id="address" value="<?php echo $address; ?>"><br>

        <!-- City -->
        <label for="city">City:</label>
        <input type="text" name="city" id="city" value="<?php echo $city; ?>"><br>

        <!-- State -->
        <label for="state">State:</label>
        <input type="text" name="state" id="state" value="<?php echo $state; ?>"><br>

        <!-- Zip Code -->
        <label for="zip_code">Zip Code:</label>
        <input type="text" name="zip_code" id="zip_code" value="<?php echo $zip_code; ?>"><br>

        <!-- Country -->
        <label for="country">Country:</label>
        <input type="text" name="country" id="country" value="<?php echo $country; ?>"><br>

        <!-- Phone Number -->
        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" id="phone_number" value="<?php echo $phone_number; ?>"><br>

        <!-- Role Selection -->
        <label for="role">Select Role:</label>
        <select name="role" id="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        <span class="error"><?php echo $role_err; ?></span><br>

        <!-- Submit Button -->
        <input type="submit" value="Register">
    </form>

    <!-- Include your JavaScript if needed -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
            padding-top: 100px;
        }

        h2 {
            color: #333;
        }

        form {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #009688;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #007b6b;
        }
    </style>
</body>
</html>

