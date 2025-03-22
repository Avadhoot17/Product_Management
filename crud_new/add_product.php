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
        $stmt = $pdo->prepare("INSERT INTO products (name, price, description, stock, image) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $price, $description, $stock, $image])) {
            header("Location: products.php");
            exit();
        } else {
            $error = "Error adding product to database.";
        }
    } else {
        $error = "Error uploading the file.";
    }
}
?>

<?php include 'header.php'; ?>

<div class="add-product-container">
    <div class="form-card">
        <div class="form-header">
            <h2><i class="fas fa-plus-circle"></i> Add New Product</h2>
            <a href="products.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Products</a>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="product-form">
            <div class="form-group">
                <label for="name"><i class="fas fa-tag"></i> Product Name</label>
                <input type="text" id="name" name="name" required placeholder="Enter product name">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price"><i class="fas fa-dollar-sign"></i> Price</label>
                    <input type="number" id="price" name="price" step="0.01" required placeholder="Enter price">
                </div>

                <div class="form-group">
                    <label for="stock"><i class="fas fa-cubes"></i> Stock</label>
                    <input type="number" id="stock" name="stock" required placeholder="Enter stock quantity">
                </div>
            </div>

            <div class="form-group">
                <label for="description"><i class="fas fa-info-circle"></i> Description</label>
                <textarea id="description" name="description" required placeholder="Enter product description" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label for="image"><i class="fas fa-image"></i> Product Image</label>
                <div class="file-input-wrapper">
                    <input type="file" id="image" name="image" accept="image/*" required>
                    <label for="image" class="file-input-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Choose an image</span>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-btn">
                    <i class="fas fa-plus"></i> Add Product
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.add-product-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 0 20px;
}

.form-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 30px;
}

.form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.form-header h2 {
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.back-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s;
}

.back-btn:hover {
    color: #007bff;
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-danger {
    background-color: #fee2e2;
    color: #dc2626;
}

.product-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    color: #4b5563;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-group input,
.form-group textarea {
    padding: 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #007bff;
    outline: none;
}

.file-input-wrapper {
    position: relative;
}

.file-input-wrapper input[type="file"] {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.file-input-label {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background: #f8f9fa;
    border: 2px dashed #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.file-input-label:hover {
    border-color: #007bff;
    background: #f0f9ff;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
}

.submit-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}

.submit-btn:hover {
    background: #0056b3;
    transform: translateY(-1px);
}

@media (max-width: 640px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
}
</style>
