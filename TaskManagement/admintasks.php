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

// Handle new task creation (without bind_param)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task'])) {
    $task_name = trim($_POST['Task_Title'] ?? '');
    $task_desc = trim($_POST['Task_Description'] ?? '');
    
    if (!empty($task_name)) {
        $task_deadline = date('Y-m-d'); 
        $list_id = 1; 

        //execute query 
        $sql = "INSERT INTO Task (Task_Title, Task_Description, Task_Deadline, List_ID) 
                VALUES ('$task_name', '$task_desc', '$task_deadline', $list_id)";

        if ($conn->query($sql) === TRUE) {
            header("Location: admintasks.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Fetch tasks 
$tasks = $conn->query("SELECT * FROM Task ORDER BY Task_ID DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tasks - Task Management</title>
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
                <li><a href="admintasks.php" class="active">Manage Tasks</a></li> 
                <li><a href="adminusers.php">Manage Users</a></li> 
                <li><a href="admingroups.php">Manage Groups</a></li>
                <li><a href="logout.php">Logout</a></li> 
            </ul>
        </nav>

        <section class="main-content">
            <h1>Manage Tasks</h1>
            
            <!-- Add Task Form -->
            <form action="admintasks.php" method="POST">
                <input type="text" name="Task_Title" placeholder="Task Name" required>
                <textarea name="Task_Description" placeholder="Task Description"></textarea>
                <button type="submit" name="add_task">Create Task</button>
            </form>

            <!-- Display Tasks -->
            <table>
                <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($tasks && $tasks->num_rows > 0) : ?>
                        <?php while ($task = $tasks->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($task['Task_Title']); ?></td>
                                <td><?php echo htmlspecialchars($task['Task_Description']); ?></td>
                                <td>
                                    <a href="edittask.php?id=<?php echo $task['Task_ID']; ?>" class="btn">Edit</a>
                                    <a href="deletetask.php?id=<?php echo $task['Task_ID']; ?>" class="btn delete-btn">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr><td colspan="3">No tasks found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
