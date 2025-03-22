<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include('db.php');

// Fetch user data
$user_id = $_SESSION['user_id'];
$query_user = "SELECT * FROM users WHERE id = :user_id";
$stmt_user = $pdo->prepare($query_user);
$stmt_user->bindParam(':user_id', $user_id);
$stmt_user->execute();
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// Fetch products
$query_products = "SELECT * FROM products";
$stmt_products = $pdo->query($query_products);
$products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);

// Count total products
$total_products = count($products);

// Calculate total stock
$total_stock = 0;
foreach ($products as $product) {
    $total_stock += $product['stock'];
}
?>

<?php include 'header.php'; ?>

<div class="dashboard-container">
    <div class="welcome-section">
        <h2><i class="fas fa-user-circle"></i> Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-info">
                <h3>Total Products</h3>
                <p><?php echo $total_products; ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-warehouse"></i>
            </div>
            <div class="stat-info">
                <h3>Total Stock</h3>
                <p><?php echo $total_stock; ?></p>
            </div>
        </div>
    </div>

    <div class="dashboard-flex">
        <!-- Left side - Add Product Form -->
        <div class="dashboard-section form-section">
            <h3><i class="fas fa-plus-circle"></i> Add New Product</h3>
            <form action="add_product.php" method="POST" enctype="multipart/form-data" class="crud-form">
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Product Name:</label>
                    <input type="text" name="name" required placeholder="Enter product name">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-dollar-sign"></i> Price:</label>
                    <input type="number" name="price" step="0.01" required placeholder="Enter price">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-info-circle"></i> Description:</label>
                    <textarea name="description" required placeholder="Enter product description"></textarea>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-cubes"></i> Stock:</label>
                    <input type="number" name="stock" required placeholder="Enter stock quantity">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-image"></i> Product Image:</label>
                    <input type="file" name="image" accept="image/*" required class="file-input">
                </div>
                <button type="submit"><i class="fas fa-plus"></i> Add Product</button>
            </form>
        </div>

        <!-- Right side - User Info -->
        <div class="dashboard-section user-info">
            <div class="profile-picture">
                <?php 
                    $initial = strtoupper(substr($user['username'], 0, 1)); 
                    echo $initial ?: 'U';
                ?>
            </div>
            <h2><i class="fas fa-user"></i> User Information</h2>
            <div class="info-card">
                <p><i class="fas fa-user"></i> <strong>Name:</strong> <span class="highlight"><?php echo htmlspecialchars($user['name'] ?? 'N/A'); ?></span></p>
                <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <span class="highlight"><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></span></p>
                <p><i class="fas fa-phone"></i> <strong>Mobile:</strong> <span class="highlight"><?php echo htmlspecialchars($user['mobile'] ?? 'N/A'); ?></span></p>
                <p><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> <span class="highlight"><?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?></span></p>
            </div>
        </div>
    </div>

    <!-- Products List -->
    <div class="dashboard-section products-section">
        <h3><i class="fas fa-list"></i> Manage Products</h3>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="product_images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div class="product-details">
                    <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                    <p class="price"><i class="fas fa-tag"></i> $<?php echo htmlspecialchars($product['price']); ?></p>
                    <p class="stock"><i class="fas fa-cube"></i> Stock: <?php echo htmlspecialchars($product['stock']); ?></p>
                    <div class="product-actions">
                        <a href="update_product.php?id=<?php echo $product['id']; ?>" class="edit-btn"><i class="fas fa-edit"></i> Edit</a>
                        <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?')"><i class="fas fa-trash"></i> Delete</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

.welcome-section {
    background: linear-gradient(135deg, #3498db, #2c3e50);
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    color: white;
}

.welcome-section h2 {
    margin: 0;
    font-size: 1.8rem;
}

.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    background: #3498db;
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.stat-icon i {
    color: white;
    font-size: 24px;
}

.stat-info h3 {
    margin: 0;
    font-size: 0.9rem;
    color: #7f8c8d;
}

.stat-info p {
    margin: 5px 0 0;
    font-size: 1.5rem;
    font-weight: bold;
    color: #2c3e50;
}

.dashboard-flex {
    display: flex;
    gap: 25px;
    margin-bottom: 25px;
}

.form-section {
    flex: 2;
}

.dashboard-section {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.dashboard-section h3 {
    color: #2c3e50;
    margin-top: 0;
    font-size: 1.5rem;
    border-bottom: 2px solid #f0f2f5;
    padding-bottom: 15px;
    margin-bottom: 20px;
}

.crud-form {
    max-width: 100%;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 2px solid #e0e6ed;
    border-radius: 8px;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #3498db;
    outline: none;
}

.file-input {
    border: 2px dashed #e0e6ed;
    padding: 20px;
    text-align: center;
    cursor: pointer;
}

/* User Info Styles */
.user-info {
    flex: 1;
    text-align: center;
    height: fit-content;
}

.profile-picture {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3498db, #2c3e50);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    margin: 0 auto 25px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.info-card {
    background: #f8fafc;
    padding: 20px;
    border-radius: 8px;
    text-align: left;
}

.info-card p {
    margin: 15px 0;
    color: #34495e;
    display: flex;
    align-items: center;
}

.info-card p i {
    width: 25px;
    color: #3498db;
}

.highlight {
    color: #2c3e50;
    font-weight: 600;
    margin-left: 5px;
}

.products-section {
    margin-top: 25px;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
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
    height: 200px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-details {
    padding: 20px;
}

.product-details h4 {
    margin: 0 0 10px;
    color: #2c3e50;
    font-size: 1.2rem;
}

.price, .stock {
    color: #7f8c8d;
    margin: 5px 0;
}

.product-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
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

button[type="submit"] {
    background: #2ecc71;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    width: 100%;
    transition: background 0.3s ease;
}

button[type="submit"]:hover {
    background: #27ae60;
}

@media (max-width: 768px) {
    .dashboard-flex {
        flex-direction: column;
    }
    
    .user-info {
        margin-top: 0;
    }

    .stats-container {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'footer.php'; ?>
