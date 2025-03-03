<?php
session_start(); // Start the session
require 'db.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT * FROM User WHERE User_Email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['User_Password'])) {
            // Set session variables
            $_SESSION['User_ID'] = $user['User_ID'];
            $_SESSION['User_Fname'] = $user['User_Fname'];
            $_SESSION['User_Lname'] = $user['User_Lname'];
            $_SESSION['User_Email'] = $user['User_Email'];
            $_SESSION['User_Role'] = $user['User_Role'];

            // Redirect to the welcome page
            header("Location: welcome.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Management</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header class="header">
        <h2 class="logo">THEOS <b>MANAGEMENT</b></h2>
    </header>

    <div class="welcome-container">
        <div class="welcome-box">
            <h1>Login</h1>
            <p>Access your tasks and manage them efficiently.</p>

            <?php if (isset($error)): ?>
                <p style='color: red;'><?= htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form class="profile-form" method="POST" action="login.php">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login</button>
           </form>

           <p>Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </div>
</body>
</html>
