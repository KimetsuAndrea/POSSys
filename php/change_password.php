<?php
session_start();

// Database connection
$mysqli = new mysqli("127.0.0.1", "0xVoid", "bilat", "nbiCorpDB");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $newPassword = trim($_POST['newPassword']);

    if (empty($username) || empty($newPassword)) {
        die("Username and password are required.");
    }

    // Check if the username exists in the database
    $stmt = $mysqli->prepare("SELECT username FROM god WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        // Username not found
        echo "Username not found, maybe you need to create that username.";
    } else {
        // Username found, update the password
        $stmt = $mysqli->prepare("UPDATE god SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $newPassword, $username);  // 'ss' means string, string

        if ($stmt->execute()) {
            echo "Password updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $mysqli->close();
}
?>

<!-- HTML form for changing password -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change User Password</title>
</head>
<body>
    <h1>Change User Password</h1>
    <form method="POST" action="change_password.php">
        <label for="username">Username:</label><br>
        <input type="text" name="username" id="username" required><br><br>
        
        <label for="newPassword">New Password:</label><br>
        <input type="password" name="newPassword" id="newPassword" required><br><br>
        
        <input type="submit" value="Change Password">
    </form>
</body>
</html>