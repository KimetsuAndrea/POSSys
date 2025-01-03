<?php
session_start();

// Redirect user to login page if not authenticated
if (!isset($_SESSION['role'])) {
    header("Location: /../index.html");
    exit;
}

// Restrict access based on role
function restrictAccess($role) {
    if ($_SESSION['role'] !== $role) {
        die("Access Denied! You don't have permission to access this page.");
    }
}
?>