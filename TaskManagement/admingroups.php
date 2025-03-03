<?php
session_start();
require 'db.php'; // Database connection
/** @var mysqli $conn */


// Redirect if not an admin
if (!isset($_SESSION['User_ID']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Ensure session values are set before concatenation
$admin_name = isset($_SESSION['first_name'], $_SESSION['last_name']) 
    ? $_SESSION['first_name'] . " " . $_SESSION['last_name'] 
    : "Admin";

// Handle new group creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_group'])) {
    $group_name = trim($_POST['group_name'] ?? '');
    
    if (!empty($group_name)) {
        $sql = "INSERT INTO GroupTable (Group_Name) VALUES ('$group_name')";
        if ($conn->query($sql) === TRUE) {
            header("Location: admingroups.php");
            exit();
        } else {
            echo "Error: " . ($conn->error ?: "Unknown error occurred.");
        }
    }
}

// Fetch all groups
$result = $conn->query("SELECT Group_ID, Group_Name FROM GroupTable ORDER BY Group_ID DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Groups - Task Management</title>
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
                <h4><?php echo htmlspecialchars($admin_name); ?></h4>
            </div>
            <ul>
                <li><a href="admin.php">Home</a></li>
                <li><a href="admintasks.php">Manage Tasks</a></li> 
                <li><a href="adminusers.php">Manage Users</a></li> 
                <li><a href="admingroups.php" class="active">Manage Groups</a></li>
                <li><a href="logout.php">Logout</a></li> 
            </ul>
        </nav>

        <section class="main-content">
            <h1>Manage Groups</h1>
            
            <!-- Add Group Form -->
            <form action="admingroups.php" method="POST">
                <input type="text" name="group_name" placeholder="Enter new group name" required>
                <button type="submit" name="add_group">Add Group</button>
            </form>

            <!-- Display Groups -->
            <table>
                <thead>
                    <tr>
                        <th>Group Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && $result->num_rows > 0) : ?>
                    <?php while ($group = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($group['Group_Name']); ?></td>
                            <td>
                                <a href="editgroup.php?id=<?php echo $group['Group_ID']; ?>" class="btn">Edit</a>
                                <a href="deletegroup.php?id=<?php echo $group['Group_ID']; ?>" class="btn delete-btn">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr><td colspan="2">No groups found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>

    <script src="/js/script.js"></script>
</body>
</html>
