<?php
// database connection
$host = '127.0.0.1';  // Change from 'localhost' to '127.0.0.1'
$user = '0xVoid';
$password = 'bilat';
$database = 'nbiCorpDB';

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully"; 
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Fetch product data
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Initialize response
$products = [];

// Fetch the data
if ($result) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $products[] = $row;
    }
}

// Return the JSON response
echo json_encode(['products' => $products]);
?>