<?php
session_start();
require 'db.php';
/** @var mysqli $conn */

// Ensure only admins can access
if (!isset($_SESSION['User_ID']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Validate and get task ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admintasks.php");
    exit();
}

$task_ID = intval($_GET['id']);

// Fetch task details
$result = $conn->query("SELECT Task_Title, Task_Description FROM Tasks WHERE Task_ID = $task_ID");
$task = $result->fetch_assoc();

if (!$task) {
    header("Location: admintasks.php");
    exit();
}

// Handle task update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_task'])) {
    $task_title = trim($_POST['Task_Title']);
    $task_description = trim($_POST['Task_Description']);

    if (!empty($task_title) && !empty($task_description)) {
        $conn->query("UPDATE Tasks SET Task_Title='$task_title', Task_Description='$task_description' WHERE Task_ID=$task_ID");
        header("Location: admintasks.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="edit-container">
        <h2>Edit Task</h2>
        <form action="" method="POST">
            <input type="text" name="Task_Title" value="<?= htmlspecialchars($task['Task_Title']); ?>" required>
            <textarea name="Task_Description" required><?= htmlspecialchars($task['Task_Description']); ?></textarea>

            <button type="submit" name="update_task">Update Task</button>
        </form>
        <a href="admintasks.php" class="btn">Cancel</a>
    </div>
</body>
</html>
