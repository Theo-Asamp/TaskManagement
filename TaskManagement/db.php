<?php
// Connect to database
$db_file = __DIR__ . "/TaskManagement.db";
$conn = new PDO("sqlite:" . $db_file);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create default Admin account
$default_email = "Admin@company.com";
$default_password = password_hash("password123", PASSWORD_DEFAULT);

// Check if the Admin already exists
$stmt = $conn->prepare("SELECT * FROM User WHERE User_Email = ?");
$stmt->execute([$default_email]);

if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
    // Insert default employee if not exists
    $insert_stmt = $conn->prepare("INSERT INTO User (User_Fname, User_Lname, User_TelNo, User_Role, User_Email, User_Password) VALUES (?, ?, ?, ?, ?, ?)");
    $insert_stmt->execute(["Theod", "Asamp", "123456789", "Admin", $default_email, $default_password]);
}
?>
