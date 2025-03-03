<?php
session_start();
require 'db.php'; // Database connection
/** @var mysqli $conn */

// Redirects if the user is not logged in or not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// This checks if first_name and last_name exist before using them
$first_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : "Admin";
$last_name = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : "";

$admin_name = trim($first_name . " " . $last_name);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <input type="checkbox" id="menu-toggle">
    <header class="header">
        <h2 class="logo">THEOS <b>MANAGEMENT</b>
            <label for="menu-toggle">
                <i id="navbtn" class="menu-icon fas fa-bars"></i>
            </label>            
        </h2>
    </header>
    
    <div class="container">
        <nav class="sidebar">
            <div class="user-profile">
                <img src="img/user.jpg" alt="Admin">
                <h4><?php echo htmlspecialchars($admin_name); ?></h4>
            </div>
            <ul>
                <li><a href="admin.php"><i class="fas fa-home"></i> <span>Home</span></a></li>
                <li><a href="admintasks.php"><i class="fas fa-tasks"></i> <span>Manage Tasks</span></a></li> 
                <li><a href="adminusers.php"><i class="fas fa-user-circle"></i> <span>Manage Users</span></a></li> 
                <li><a href="admingroups.php"><i class="fas fa-users"></i> <span>Manage Groups</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li> 
            </ul>
        </nav>        
        
        <section class="main-content">
            <h1>WELCOME, <?php echo htmlspecialchars($admin_name); ?>!</h1>
            <p>Here you can manage your tasks professionally.</p>
            <a href="admingroups.php" class="btn manage-tasks-btn">Manage Groups</a>
        </section>
    </div>

    <script src="/js/script.js"></script>
</body>
</html>
