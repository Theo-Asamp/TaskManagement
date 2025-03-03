<?php
require_once 'session.php';
?>

<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch user details
$sql = "SELECT * FROM User WHERE User_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST["first-name"];
    $lname = $_POST["last-name"];
    $phone = $_POST["phone"];

    $update_sql = "UPDATE User SET User_Fname = ?, User_Lname = ?, User_TelNo = ? WHERE User_ID = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->execute([$fname, $lname, $phone, $user_id]);

    $_SESSION["user_name"] = $fname . " " . $lname;
    header("Location: profile.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Task Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header class="header">
        <h2 class="logo">THEOS <b>MANAGEMENT</b></h2>
    </header>

    <div class="container">
        <nav class="sidebar">
            <div class="user-profile">
                <img src="img/user.jpg" alt="User">
                <h4><?php echo htmlspecialchars($_SESSION["user_name"]); ?></h4>
            </div>
            <ul>
                <li><a href="welcome.php"><i class="fas fa-home"></i> <span>Home</span></a></li>
                <li><a href="tasks.php"><i class="fas fa-tasks"></i> <span>My Tasks</span></a></li>
                <li><a href="profile.php" class="active"><i class="fas fa-user-circle"></i> <span>Profile</span></a></li> 
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li> 
            </ul>
        </nav>

        <section class="main-content">
            <div class="profile-box">
                <h1>My Profile</h1>

                <?php if (isset($_GET["success"])): ?>
                    <p class="success-msg">Profile updated successfully!</p>
                <?php endif; ?>

                <form method="POST">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" name="first-name" value="<?php echo htmlspecialchars($user["User_Fname"]); ?>" required>
                    
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" name="last-name" value="<?php echo htmlspecialchars($user["User_Lname"]); ?>" required>
                    
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user["User_TelNo"]); ?>" required>
                    
                    <label for="role">Role</label>
                    <input type="text" id="role" name="role" value="<?php echo htmlspecialchars($user["User_Role"]); ?>" disabled>
                    
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user["User_Email"]); ?>" disabled>
                    
                    <button type="submit">Update Profile</button>
                </form>
            </div>
        </section>
    </div>

    <script src="/js/script.js"></script>
</body>
</html>
