<?php
require_once 'db.php';
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = trim($_POST["first_name"]);
    $lname = trim($_POST["last_name"]);
    $phone = trim($_POST["phone"]);
    $role = $_POST["role"];
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM User WHERE User_Email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $error = "Email is already registered!";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $sql = "INSERT INTO User (User_Fname, User_Lname, User_TelNo, User_Role, User_Email, User_Password) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$fname, $lname, $phone, $role, $email, $hashed_password])) {
            header("Location: login.php?success=registered");
            exit();
        } else {
            $error = "Registration failed! Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Task Management</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header class="header">
        <h2 class="logo">THEOS <b>MANAGEMENT</b></h2>
    </header>

    <div class="welcome-box">
        <h1>Register</h1>
        <p>Create an account to manage your tasks efficiently.</p>


        <form class="profile-form" action="register.php" method="POST">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" required>

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="employee">Employee</option>
                <option value="admin">Admin</option>
            </select>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Register</button>
        </form>                        

        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
