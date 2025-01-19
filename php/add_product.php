<?php
session_start();

// Database connection
$mysqli = new mysqli("127.0.0.1", "0xVoid", "bilat", "nbiCorpDB");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = trim($_POST['productName']);
    $productPrice = trim($_POST['productPrice']);
    $productCategory = trim($_POST['productCategory']);
    $productQuantity = isset($_POST['productQuantity']) ? trim($_POST['productQuantity']) : 0; // Default quantity to 0 if not provided
    
    // Get the uploaded image
    $productImage = $_FILES['productImage'];

    if (empty($productName) || empty($productPrice) || empty($productCategory)) {
        die("All fields are required.");
    }

    // Ensure the 'img' directory exists
    $uploadDirectory = "/data/data/com.termux/files/home/POS/img/";  // Default image directory
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true); // Create directory if it doesn't exist
    }

    // Generate a unique file name to avoid overwriting existing files
    $uploadedImageName = $uploadDirectory . basename($_FILES['productImage']['name']);

    // Check if the file is uploaded successfully
    if ($_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        // Move the uploaded file to the img/ directory
        if (move_uploaded_file($productImage['tmp_name'], $uploadedImageName)) {
            // Prepare the SQL statement to insert the new product into the database
            $stmt = $mysqli->prepare("INSERT INTO products (name, price, category, imageUrl, quantity) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $productName, $productPrice, $productCategory, $uploadedImageName, $productQuantity);  // 'ssssi' means string, string, string, string, int

            if ($stmt->execute()) {
                echo "New product added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "No file uploaded or there was an upload error.";
    }

    $mysqli->close();
}
?>

<!-- HTML form for adding a new product -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
</head>
<body>
    <h1>Add New Product</h1>
    <form method="POST" action="add_product.php" enctype="multipart/form-data">
        <label for="productName">Product Name:</label><br>
        <input type="text" name="productName" id="productName" required><br><br>
        
        <label for="productPrice">Product Price:</label><br>
        <input type="text" name="productPrice" id="productPrice" required><br><br>
        
        <label for="productCategory">Product Category:</label><br>
        <input type="text" name="productCategory" id="productCategory" required><br><br>
        
        <label for="productQuantity">Product Quantity:</label><br>
        <input type="number" name="productQuantity" id="productQuantity" value="0" required><br><br> <!-- Default quantity to 0 -->
        
        <label for="productImage">Product Image:</label><br>
        <input type="file" name="productImage" id="productImage" required><br><br>
        
        <input type="submit" value="Add Product">
    </form>
</body>
</html>