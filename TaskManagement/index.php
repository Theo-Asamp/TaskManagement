<?php
session_start();
require 'db.php'; // Database connection


// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);

// Redirect to the dashboard **only** if the user is logged in **and** not already there
if (isset($_SESSION['user_id']) && $current_page !== "index.php") {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Task Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header class="header">
        <h2 class="logo">THEOS <b>MANAGEMENT</b></h2>
    </header>
    
    <div class="welcome-container">
        <div class="welcome-box">
            <h1>Welcome to Task Management</h1>
            <p>Organize your tasks efficiently and boost productivity.</p>
            
            <div class="auth-buttons">
                <a href="login.php" class="btn login-btn">Login</a>
                <a href="register.php" class="btn register-btn">Register</a>
            </div>
        </div>
    </div>

    <script src="/js/script.js"></script>
</body>
</html>
