<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];
    
    // Handle file upload
    $image = $_FILES['image']['name'];
    $target = "product_images/" . basename($image);

    // Move the uploaded file to the product_images folder
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        // File uploaded successfully
    } else {
        // Handle error
        echo "Error uploading the file.";
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO products (name, price, description, stock, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $price, $description, $stock, $image]);

    header("Location: products.php");
    exit();
}
?>

<head>
    <style>
        .container {
            width: 50%;
            margin: auto;
            padding: 20px;
            text-align: center;
            background: #f9f9f9;
            box-shadow: 0px 0px 10px rgb(0 0 0);
            border-radius: 10px;
            margin-top: 50px;
        }
        .input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<!-- HTML form for adding product -->
<div class="container">
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" required placeholder="Product Name" class="input">
        <input type="number" name="price" required placeholder="Price" class="input">
        <textarea name="description" required placeholder="Description" class="input"></textarea>
        <input type="number" name="stock" required placeholder="Stock" class="input">
        <input type="file" name="image" required class="input">
        <button type="submit" class="button">Add Product</button>
    </form>
</div>
