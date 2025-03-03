<?php
require 'session.php';

// Ensure the user is logged in
if (!isset($_SESSION['User_ID'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['User_ID'];

try {
    // Check if the user has a Task List
    $list_stmt = $conn->prepare("SELECT List_ID, TaskList_Name, TaskList_Description FROM Task_List WHERE User_ID = ?");
    $list_stmt->execute([$user_id]);
    $list = $list_stmt->fetch(PDO::FETCH_ASSOC);

    $list_id = $list['List_ID'] ?? null;

    // Handle Task List creation
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create_list'])) {
        $list_name = trim($_POST['list_name']);
        $list_description = trim($_POST['list_description']);

        if (!empty($list_name) && !empty($list_description)) {
            $create_list_stmt = $conn->prepare("INSERT INTO Task_List (TaskList_Name, TaskList_Description, User_ID) VALUES (?, ?, ?)");
            $create_list_stmt->execute([$list_name, $list_description, $user_id]);

            header("Location: tasks.php");
            exit();
        }
    }

    // If the user has a task list, allow task management
    $tasks = [];
    if ($list_id) {
        // Fetch tasks belonging to this list
        $tasks_stmt = $conn->prepare("SELECT * FROM Task WHERE List_ID = ? ORDER BY Task_Deadline ASC");
        $tasks_stmt->execute([$list_id]);
        $tasks = $tasks_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Handle task addition
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_task'])) {
            $task_title = trim($_POST['task']);
            $task_description = trim($_POST['task_description']);

            if (!empty($task_title) && !empty($task_description)) {
                $task_stmt = $conn->prepare("INSERT INTO Task (Task_Title, Task_Description, Task_Deadline, List_ID) 
                                             VALUES (?, ?, '2025-12-31', ?)");
                $task_stmt->execute([$task_title, $task_description, $list_id]);
            }

            header("Location: tasks.php");
            exit();
        }

        // Handle task deletion
        if (!empty($_GET['delete'])) {
            $task_id = intval($_GET['delete']);
            $conn->prepare("DELETE FROM Task WHERE Task_ID = ? AND List_ID = ?")
                ->execute([$task_id, $list_id]);
            header("Location: tasks.php");
            exit();
        }

        // Handle task editing
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['edit_task'])) {
            $task_id = intval($_POST['task_id']);
            $task_title = trim($_POST['task']);
            $task_description = trim($_POST['task_description']);

            if (!empty($task_title) && !empty($task_description)) {
                $update_stmt = $conn->prepare("UPDATE Task SET Task_Title = ?, Task_Description = ? WHERE Task_ID = ?");
                $update_stmt->execute([$task_title, $task_description, $task_id]);
            }

            header("Location: tasks.php");
            exit();
        }
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks</title>
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
                <li><a href="tasks.php" class="active"><i class="fas fa-tasks"></i> <span>My Tasks</span></a></li> 
                <li><a href="profile.php"><i class="fas fa-user-circle"></i> <span>Profile</span></a></li> 
                <li><a href="groups.php"><i class="fas fa-users"></i> <span>Groups</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </nav> 

        <section class="main-content">
            <div class="task-container">
                <h1>My Tasks</h1>

                <?php if (!$list_id): ?>
                    <!-- If no task list, show form to create one -->
                    <form method="POST">
                        <input type="text" name="list_name" placeholder="Enter Task List Name" required>
                        <textarea name="list_description" placeholder="Enter Task List Description" required></textarea>
                        <button type="submit" name="create_list">Create Task List</button>
                    </form>
                <?php else: ?>
                    <!-- Display Task List Details -->
                    <h2><?php echo htmlspecialchars($list['TaskList_Name']); ?></h2>
                    <p><?php echo htmlspecialchars($list['TaskList_Description']); ?></p>

                    <!-- If task list exists, allow task management -->
                    <form method="POST">
                        <input type="text" name="task" placeholder="Enter new task" required>
                        <textarea name="task_description" placeholder="Enter task description" required></textarea>
                        <button type="submit" name="add_task">Add Task</button>
                    </form>

                    <ul class="task-list">
                        <?php foreach ($tasks as $task): ?>
                            <li>
                                <strong><?= htmlspecialchars($task['Task_Title']) ?></strong><br>
                                <small><?= htmlspecialchars($task['Task_Description']) ?></small>
                                <div class="task-actions">
                                    <a href="?edit=<?= $task['Task_ID'] ?>"><i class="fas fa-edit"></i></a>
                                    <a href="?delete=<?= $task['Task_ID'] ?>"><i class="fas fa-trash"></i></a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <?php if (!empty($_GET['edit'])): 
                        $task_id = intval($_GET['edit']);
                        $task_stmt = $conn->prepare("SELECT * FROM Task WHERE Task_ID = ?");
                        $task_stmt->execute([$task_id]);
                        $task_to_edit = $task_stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <form method="POST">
                        <input type="hidden" name="task_id" value="<?= $task_to_edit['Task_ID'] ?>">
                        <input type="text" name="task" value="<?= htmlspecialchars($task_to_edit['Task_Title']) ?>" required>
                        <textarea name="task_description" required><?= htmlspecialchars($task_to_edit['Task_Description']) ?></textarea>
                        <button type="submit" name="edit_task">Update Task</button>
                    </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</body>
</html>
