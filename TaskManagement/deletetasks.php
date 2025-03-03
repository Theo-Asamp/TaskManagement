<?php
session_start();
require 'db.php';
/** @var mysqli $conn */
?>

<?php
session_start();
require 'db.php';

// Only admins can delete tasks
if (!isset($_SESSION['User_ID']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Validate and get task ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admintasks.php");
    exit();
}

$task_id = intval($_GET['id']);


$stmt = $conn->prepare("DELETE FROM tasks WHERE Task_ID = ?");


// Redirect to tasks page
header("Location: admintasks.php");
exit();
?>
