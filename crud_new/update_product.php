<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];
    
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $target = "product_images/" . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // File uploaded successfully
        } else {
            $error = "Error uploading the file.";
        }
    } else {
        $image = $product['image'];
    }

    $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, description = ?, stock = ?, image = ? WHERE id = ?");
    if($stmt->execute([$name, $price, $description, $stock, $image, $id])) {
        $success = "Product updated successfully!";
    } else {
        $error = "Error updating the product.";
    }
}
?>

<?php include 'header.php'; ?>

<div class="update-container">
    <!-- Top Navigation Bar -->
    <div class="top-nav">
        <div class="nav-left">
            <h2><i class="fas fa-edit"></i> Update Product</h2>
        </div>
        <div class="nav-right">
            <a href="products.php" class="nav-btn"><i class="fas fa-arrow-left"></i> Back to Products</a>
        </div>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert success">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert error">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="update-form-container">
        <form method="POST" enctype="multipart/form-data" class="update-form">
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Product Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-dollar-sign"></i> Price</label>
                    <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-info-circle"></i> Description</label>
                <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-cube"></i> Stock</label>
                    <input type="number" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-image"></i> Product Image</label>
                    <input type="file" name="image" accept="image/*" class="file-input">
                    <p class="input-hint">Leave empty to keep current image</p>
                </div>
            </div>

            <div class="current-image">
                <label>Current Image:</label>
                <img src="product_images/<?php echo htmlspecialchars($product['image']); ?>" alt="Current product image">
            </div>

            <div class="form-actions">
                <button type="submit" class="update-btn"><i class="fas fa-save"></i> Update Product</button>
                <a href="products.php" class="cancel-btn"><i class="fas fa-times"></i> Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
.update-container {
    padding: 20px;
    max-width: 1000px;
    margin: 0 auto;
}

/* Top Navigation Bar */
.top-nav {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-bottom: 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-left h2 {
    color: #2c3e50;
    margin: 0;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.nav-right {
    display: flex;
    gap: 15px;
}

.nav-btn {
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    color: white;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: opacity 0.3s ease;
    background: linear-gradient(135deg, #3498db, #2c3e50);
}

.nav-btn:hover {
    opacity: 0.9;
}

/* Alert Messages */
.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert.success {
    background: #d4edda;
    color: #155724;
}

.alert.error {
    background: #f8d7da;
    color: #721c24;
}

/* Form Container */
.update-form-container {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.update-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-row {
    display: flex;
    gap: 20px;
}

.form-group {
    flex: 1;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #2c3e50;
    font-weight: 600;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e6ed;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-group textarea {
    height: 120px;
    resize: vertical;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #3498db;
    outline: none;
}

.input-hint {
    font-size: 0.9rem;
    color: #7f8c8d;
    margin-top: 5px;
}

.file-input {
    border: 2px dashed #e0e6ed !important;
    padding: 20px !important;
    text-align: center;
    cursor: pointer;
}

.current-image {
    margin: 20px 0;
}

.current-image label {
    display: block;
    margin-bottom: 10px;
    color: #2c3e50;
    font-weight: 600;
}

.current-image img {
    max-width: 200px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.update-btn,
.cancel-btn {
    padding: 12px 25px;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: opacity 0.3s ease;
}

.update-btn {
    background: linear-gradient(135deg, #3498db, #2c3e50);
    color: white;
    border: none;
    flex: 1;
}

.cancel-btn {
    background: #e74c3c;
    color: white;
    text-decoration: none;
    padding: 12px 30px;
}

.update-btn:hover,
.cancel-btn:hover {
    opacity: 0.9;
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
        gap: 20px;
    }

    .top-nav {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .nav-right {
        width: 100%;
        justify-content: center;
    }

    .form-actions {
        flex-direction: column;
    }

    .cancel-btn {
        text-align: center;
    }
}
</style>

<?php include 'footer.php'; ?>
