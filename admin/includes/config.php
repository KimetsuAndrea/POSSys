<?php 
// DB credentials.
define('DB_HOST','127.0.0.1');
define('DB_USER','0xVoid');
define('DB_PASS','bilat');
define('DB_NAME','db_admin');
// Establish database connection.
try
{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}
?>