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
        // Correct the image URL without escaping slashes
        $row['imageUrl'] = '/img/' . basename($row['imageUrl']);
        $products[] = $row;
    }
}

// Return the JSON response without escaped slashes
echo json_encode(['products' => $products], JSON_UNESCAPED_SLASHES);
?>