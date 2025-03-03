<?php
require_once 'session.php';
?>

<?php
require 'session.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

echo "Welcome, " . $_SESSION['name'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
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
                <img src="img/user.jpg" alt="User">
                <h4><?php echo htmlspecialchars($_SESSION["user_name"]); ?></h4> <!-- Display Logged-in User -->
            </div>
            <ul>
                <li><a href="welcome.php" class="active"><i class="fas fa-home"></i> <span>Home</span></a></li>
                <li><a href="tasks.php"><i class="fas fa-tasks"></i> <span>My Tasks</span></a></li> 
                <li><a href="profile.php"><i class="fas fa-user-circle"></i> <span>Profile</span></a></li> 
                <li><a href="groups.php"><i class="fas fa-users"></i> <span>My Group</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li> <!-- Secure Logout -->
            </ul>
        </nav>        
        
        <section class="main-content">
            <div class="home-box">
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?></h1>
                <p>Organize your tasks efficiently and improve productivity.</p>
                <a href="tasks.php" class="btn manage-tasks-btn">Manage Tasks</a>
            </div>
        </section>
    </div>

    <script src="/js/script.js"></script>
</body>
</html>
