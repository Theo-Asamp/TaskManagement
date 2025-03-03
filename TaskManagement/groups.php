<?php
session_start();
require 'db.php'; // Database connection

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get logged-in user ID
$user_id = $_SESSION['user_id'];

// Fetch groups where the user is a member
$result = $conn->prepare("SELECT Group_ID, Group_Name FROM GroupTable WHERE User_ID = ?");
$result->execute([$user_id]);
$groups = $result->fetchAll(PDO::FETCH_ASSOC);

// Handle leaving a group
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['leave_group'])) {
    $group_id = intval($_POST['group_id']);
    
    $leave_stmt = $conn->prepare("DELETE FROM GroupTable WHERE Group_ID = ? AND User_ID = ?");
    $leave_stmt->execute([$group_id, $user_id]);

    header("Location: groups.php?left=success");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Groups - Task Management</title>
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
                <li><a href="welcome.php">Home</a></li>
                <li><a href="tasks.php">My Tasks</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="groups.php" class="active">My Groups</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <section class="main-content">
            <h1>My Groups</h1>

            <table>
                <thead>
                    <tr>
                        <th>Group Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($groups)) : ?>
                    <?php foreach ($groups as $group) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($group['Group_Name']); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="group_id" value="<?php echo $group['Group_ID']; ?>">
                                    <button type="submit" name="leave_group" class="btn leave-btn">Leave Group</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr><td colspan="2">You are not in any groups.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>

    <script src="/js/script.js"></script>
</body>
</html>
