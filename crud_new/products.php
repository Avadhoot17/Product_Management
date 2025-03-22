<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>

<?php include 'header.php'; ?>

<div class="products-container">
    <!-- Top Navigation Bar -->
    <div class="top-nav">
        <div class="nav-left">
            <h2><i class="fas fa-box"></i> Products</h2>
        </div>
        <div class="nav-right">
            <a href="add_product.php" class="nav-btn add-product-btn">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
        <div class="product-card">
            <div class="product-image">
                <img src="product_images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="product-details">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p class="price"><i class="fas fa-tag"></i> $<?php echo htmlspecialchars($product['price']); ?></p>
                <p class="description"><i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($product['description']); ?></p>
                <p class="stock"><i class="fas fa-cube"></i> Stock: <?php echo htmlspecialchars($product['stock']); ?></p>
                <div class="product-actions">
                    <a href="update_product.php?id=<?php echo $product['id']; ?>" class="edit-btn">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="delete-btn" 
                       onclick="return confirm('Are you sure you want to delete this product?')">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.products-container {
    padding: 20px;
    max-width: 1400px;
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
}

.nav-btn:hover {
    opacity: 0.9;
}

.nav-btn i {
    font-size: 1.1rem;
}

.nav-btn:first-child {
    background: linear-gradient(135deg, #3498db, #2c3e50);
}

.add-product-btn {
    background: #2ecc71;
}

/* Products Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
}

.product-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 12px rgba(0,0,0,0.15);
}

.product-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
    border-radius: 8px 8px 0 0;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #fff;
    padding: 10px;
}

.product-image img {
    max-width: 180px;
    max-height: 180px;
    width: auto;
    height: auto;
    object-fit: contain;
    display: block;
}

.product-details {
    padding: 20px;
}

.product-details h3 {
    margin: 0 0 15px;
    color: #2c3e50;
    font-size: 1.3rem;
}

.product-details p {
    margin: 10px 0;
    color: #7f8c8d;
    display: flex;
    align-items: center;
    gap: 8px;
}

.price {
    font-size: 1.2rem;
    color: #2c3e50 !important;
    font-weight: 600;
}

.description {
    font-size: 0.9rem;
    line-height: 1.5;
}

.stock {
    font-weight: 500;
}

.product-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.edit-btn,
.delete-btn {
    flex: 1;
    padding: 8px 15px;
    border-radius: 6px;
    text-decoration: none;
    color: white;
    font-size: 14px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    transition: opacity 0.3s ease;
}

.edit-btn {
    background: #3498db;
}

.delete-btn {
    background: #e74c3c;
}

.edit-btn:hover,
.delete-btn:hover {
    opacity: 0.9;
}

@media (max-width: 768px) {
    .top-nav {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .nav-right {
        width: 100%;
        justify-content: center;
    }

    .products-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'footer.php'; ?>
