<?php
session_start();
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session

// Redirect to the correct index.html 
header("Location: /index.html"); 
exit;
?>
