<?php
// Start session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once 'db.php';

// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);

// Debugging: Check if session is set properly (REMOVE AFTER DEBUGGING)
if (!isset($_SESSION['User_ID'])) {
    // Uncomment to debug session variables:
    // var_dump($_SESSION);
    // exit(); // Stop script for debugging
}

// If the user is logged in and trying to access login/register, redirect them
if (isset($_SESSION['User_ID']) && ($current_page == "login.php" || $current_page == "register.php")) {
    header("Location: welcome.php");
    exit();
}

// If user is logged in but session details are missing, fetch them
if (isset($_SESSION['User_ID']) && !isset($_SESSION['User_Fname'])) {
    $user_id = $_SESSION['User_ID'];

    // Fetch user details
    $stmt = $conn->prepare("SELECT User_Fname, User_Lname, User_Email, User_Role FROM User WHERE User_ID = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['User_Fname'] = $user['User_Fname'];
        $_SESSION['User_Lname'] = $user['User_Lname'];
        $_SESSION['User_Email'] = $user['User_Email'];
        $_SESSION['User_Role'] = $user['User_Role'];
    } else {
        // User not found, destroy session and redirect
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

// If user is not logged in, redirect to login unless they are on login/register pages
if (!isset($_SESSION['User_ID']) && !in_array($current_page, ["login.php", "register.php"])) {
    header("Location: login.php");
    exit();
}
?>
