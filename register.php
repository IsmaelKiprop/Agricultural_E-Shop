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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('images/agri\ product_5.png'); /* Update the path to your background image */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            text-align: center;
            padding-top: 100px;
            margin: 0;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 5px;
            padding: 20px;
            max-width: 500px;
            margin: 0 auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        .registration-form h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn-register {
            background-color: #009688;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-register:hover {
            background-color: #007b6b;
        }

        .login-link {
            text-decoration: none;
            color: #009688;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <form class="registration-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h2>Registration</h2>
            <!-- Username -->
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo $username; ?>">
                <span class="error"><?php echo $username_err; ?></span>
            </div>

            <!-- Email -->
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" value="<?php echo $email; ?>">
                <span class="error"><?php echo $email_err; ?></span>
            </div>

            <!-- Password -->
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" value="<?php echo $password; ?>">
                <span class="error"><?php echo $password_err; ?></span>
            </div>

            <!-- First Name -->
            <div class="input-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" value="<?php echo $first_name; ?>">
            </div>

            <!-- Last Name -->
            <div class="input-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>">
            </div>

            <!-- Address -->
            <div class="input-group">
                <label for="address">Address:</label>
                <input type="text" name="address" id="address" value="<?php echo $address; ?>">
            </div>

            <!-- City -->
            <div class="input-group">
                <label for="city">City:</label>
                <input type="text" name="city" id="city" value="<?php echo $city; ?>">
            </div>

            <!-- State -->
            <div class="input-group">
                <label for="state">State:</label>
                <input type="text" name="state" id="state" value="<?php echo $state; ?>">
            </div>

            <!-- Zip Code -->
            <div class="input-group">
                <label for="zip_code">Zip Code:</label>
                <input type="text" name="zip_code" id="zip_code" value="<?php echo $zip_code; ?>">
            </div>

            <!-- Country -->
            <div class="input-group">
                <label for="country">Country:</label>
                <input type="text" name="country" id="country" value="<?php echo $country; ?>">
            </div>

            <!-- Phone Number -->
            <div class="input-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" id="phone_number" value="<?php echo $phone_number; ?>">
            </div>

            <!-- Role Selection -->
            <div class="input-group">
                <label for="role">Select Role:</label>
                <select name="role" id="role">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <span class="error"><?php echo $role_err; ?></span>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-register">Register</button>
        </form>

        <!-- Login Link -->
        <p>Already have an account? <a href="login.php" class="login-link">Login here</a></p>
    </div>
</body>
</html>



