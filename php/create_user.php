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
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    if (empty($username) || empty($password) || empty($role)) {
        die("All fields are required.");
    }

    // Directly use the plain password (no hashing)
    $plainPassword = $password;

    // Prepare the SQL statement to insert the new user into the database
    $stmt = $mysqli->prepare("INSERT INTO god (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $plainPassword, $role);  // 'sss' means string, string, string

    if ($stmt->execute()) {
        echo "New user created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>

<!-- HTML form for creating a new user -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New User</title>
</head>
<body>
    <h1>Create New User</h1>
    <form method="POST" action="create_user.php">
        <label for="username">Username:</label><br>
        <input type="text" name="username" id="username" required><br><br>
        
        <label for="password">Password:</label><br>
        <input type="password" name="password" id="password" required><br><br>
        
        <label for="role">Role:</label><br>
        <select name="role" id="role">
            <option value="admin">Admin</option>
            <option value="cashier">Cashier</option>
        </select><br><br>
        
        <input type="submit" value="Create User">
    </form>
</body>
</html>