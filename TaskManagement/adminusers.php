<?php
session_start();
require 'db.php'; // Database connection
/** @var mysqli $conn */


// Fetch all users
$sql = "SELECT User_ID, first_name, last_name, email, role FROM users";
$result = $conn->query($sql);

// Handle user deletion
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $conn->query("DELETE FROM users WHERE User_ID = $user_id");
    header("Location: adminusers.php");
    exit();
}

// Handle role update
if (isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];
    $conn->query("UPDATE users SET role = '$role' WHERE User_ID = $user_id");
    header("Location: adminusers.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
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
                <img src="img/user.jpg" alt="Admin">
                <h4>Admin</h4>
            </div>
            <ul>
                <li><a href="admin.php"><i class="fas fa-home"></i> <span>Home</span></a></li>
                <li><a href="admintasks.php"><i class="fas fa-tasks"></i> <span>Manage Tasks</span></a></li>
                <li><a href="adminusers.php" class="active"><i class="fas fa-user-circle"></i> <span>Manage Users</span></a></li>
                <li><a href="admingroups.php"><i class="fas fa-users"></i> <span>Manage Groups</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </nav>
        
        <section class="main-content">
            <h1>Manage Users</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_row()): ?>
                    <tr>
                        <td><?= $row[0]; ?></td>
                        <td><?= $row[1] . ' ' . $row[2]; ?></td>
                        <td><?= $row[3]; ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="user_id" value="<?= $row[0]; ?>">
                                <select name="role">
                                    <option value="employee" <?= $row[4] == 'employee' ? 'selected' : ''; ?>>Employee</option>
                                    <option value="admin" <?= $row[4] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                </select>
                                <button type="submit" name="update_role">Update</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="user_id" value="<?= $row[0]; ?>">
                                <button type="submit" name="delete_user" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
