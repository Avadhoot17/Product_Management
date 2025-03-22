<?php
if(!isset($_SESSION)) {
    session_start();
}
?>
<div class="sidebar">
    <div class="sidebar-header">
        <h3><i class="fas fa-tachometer-alt"></i> Navigation</h3>
    </div>
    <ul class="sidebar-menu">
        <li><a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i> Dashboard</a>
        </li>
        <li><a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">
            <i class="fas fa-box"></i> Products</a>
        </li>
        <?php if(isset($_SESSION['user_id'])): ?>
            <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <?php else: ?>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
            <li><a href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
        <?php endif; ?>
    </ul>
</div>

<style>
.sidebar {
    width: 250px;
    background: linear-gradient(180deg, #2c3e50 0%, #3498db 100%);
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    color: white;
    padding-top: 20px;
    transition: all 0.3s ease;
}

.sidebar-header {
    padding: 20px;
    text-align: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-header h3 {
    font-size: 1.5rem;
    margin: 0;
    color: white;
}

.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 20px 0;
}

.sidebar-menu li {
    margin: 8px 0;
}

.sidebar-menu li a {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    padding: 12px 20px;
    transition: all 0.3s ease;
    font-size: 1.1rem;
}

.sidebar-menu li a i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

.sidebar-menu li a:hover, .sidebar-menu li a.active {
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(5px);
    border-radius: 5px;
}

/* Adjust main content to accommodate sidebar */
body {
    margin-left: 250px;
    background-color: #f4f6f9;
}

.header {
    margin-left: 0;
    width: calc(100% - 250px);
}
</style>
