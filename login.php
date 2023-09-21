<?php
// Include your database connection file
include("connection/db.php");

// Define variables to store user input and error messages
$username = $password = "";
$username_err = $password_err = "";

// Check if the login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize user inputs
    $username = test_input($_POST["username"]);
    $password = test_input($_POST["password"]);

    // Check if username is empty
    if (empty($username)) {
        $username_err = "Please enter your username.";
    }

    // Check if password is empty
    if (empty($password)) {
        $password_err = "Please enter your password.";
    }

    // If there are no validation errors, attempt to log in
    if (empty($username_err) && empty($password_err)) {
        // Check if the username exists in the database
        $sql = "SELECT id, username, password, role FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $user_id = $row["id"];
            $hashed_password = $row["password"];
            $user_role = $row["role"];

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Start a session
                session_start();

                // Store user information in the session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['user_role'] = $user_role;

                // Redirect based on user role
                if ($user_role == 'admin') {
                    header("Location: admin.php"); // Redirect admin to the admin dashboard
                } else {
                    header("Location: index.php"); // Redirect users to the index page
                }
                exit();
            } else {
                // Password is incorrect
                $password_err = "Invalid password.";
            }
        } else {
            // Username not found
            $username_err = "Username not found.";
        }
    }
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
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('images/agri_product_1.png'); /* Update the path to your background image */
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
            max-width: 300px;
            margin: 0 auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        .login-form h2 {
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
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn-login {
            background-color: #009688;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-login:hover {
            background-color: #007b6b;
        }

        .registration-link {
            text-decoration: none;
            color: #009688;
        }

        .registration-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <form class="login-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h2>Login</h2>
            <!-- Username -->
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo $username; ?>">
                <span class="error"><?php echo $username_err; ?></span>
            </div>

            <!-- Password -->
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" value="<?php echo $password; ?>">
                <span class="error"><?php echo $password_err; ?></span>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-login">Login</button>
        </form>

        <!-- Registration Link -->
        <p>Don't have an account? <a href="register.php" class="registration-link">Register here</a></p>
    </div>
</body>
</html>
