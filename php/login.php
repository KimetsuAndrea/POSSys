<?php
session_start();

$users = [
    'admin' => ['username' => 'admin', 'password' => password_hash('admin123', PASSWORD_BCRYPT)],
    'cashier' => ['username' => 'cashier', 'password' => password_hash('cashier123', PASSWORD_BCRYPT)],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    if (empty($username) || empty($password) || empty($role)) {
        die("All fields are required.");
    }

    if (isset($users[$role]) && $users[$role]['username'] === $username) {
        if (password_verify($password, $users[$role]['password'])) {
            $_SESSION['role'] = $role;
            if ($role === 'admin') {
                header("Location: /../dashboards/admin.html");
            } else {
                header("Location: /../dashboards/cashier.html");
            }
            exit;
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Invalid username or role.";
    }
}
?>