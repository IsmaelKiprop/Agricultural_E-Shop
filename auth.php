<?php
// auth.php

// Start a session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or show an error message
    header("Location: login.php");
    exit();
}

// Define user roles (you can customize this based on your roles)
$allowed_roles = ['admin', 'user'];

// Check if the user has a valid role
if (!in_array($_SESSION['user_role'], $allowed_roles)) {
    // Redirect users to index.php if they have a "user" role
    if ($_SESSION['user_role'] === 'user') {
        header("Location: index.php");
        exit();
    }
    
    // Check if there is already an admin logged in
    if ($_SESSION['user_role'] === 'admin') {
        // Redirect to the admin dashboard if an admin is already logged in
        header("Location: admin_dashboard.php");
        exit();
    }
}

// If no role-specific condition is met, continue
?>


