<?php
$adminPassword = password_hash('admin123', PASSWORD_BCRYPT);
$cashierPassword = password_hash('cashier123', PASSWORD_BCRYPT);

echo "Admin Password: " . $adminPassword . "<br>";
echo "Cashier Password: " . $cashierPassword;
?>