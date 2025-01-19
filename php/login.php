<?php
session_start();

// Database connection (use your actual database credentials)
$mysqli = new mysqli("127.0.0.1", "0xVoid", "bilat", "nbiCorpDB");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    if (empty($username) || empty($password) || empty($role)) {
        die("All fields are required.");
    }

    // Query to fetch the user details from the 'god' table
    $stmt = $mysqli->prepare("SELECT username, password, role FROM god WHERE username = ? AND role = ?");
    $stmt->bind_param("ss", $username, $role); // 'ss' means both are strings
    $stmt->execute();
    $stmt->store_result();
    
    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($dbUsername, $dbPassword, $dbRole);
        $stmt->fetch();

        // Compare plain text password
        if ($password === $dbPassword) {
            $_SESSION['role'] = $dbRole;
            if ($dbRole === 'admin') {
                header("Location: /dashboard/admin.html");
            } else {
                header("Location: /dashboard/cashier.html");
            }
            exit;
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Invalid username or role.";
    }

    $stmt->close();
    $mysqli->close();
}
?>