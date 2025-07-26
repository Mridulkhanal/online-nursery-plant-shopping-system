<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db_connect.php';
$user_name = '';
$user_role = '';
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT name, role FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result ? $result->fetch_assoc() : null;
    if ($user && is_array($user)) {
        $user_name = htmlspecialchars($user['name'] ?: 'Guest');
        $user_role = $user['role'] ?: '';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Nursery</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/customer.css">
    <script src="../js/customer.js" defer></script>
</head>
<body>
    <header>
        <nav>
            <div class="nav-left">
                <a href="index.php" class="logo">Online Nursery</a>
                <a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">Products</a>
                <a href="contact.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Contact</a>
                <a href="about.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">About Us</a>
                <?php if ($user_role === 'admin'): ?>
                    <a href="admin/admin_dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php' ? 'active' : ''; ?>">Admin Panel</a>
                <?php endif; ?>
            </div>
            <div class="nav-right">
                <?php if ($user_name && $user_name !== 'Guest'): ?>
                    <div class="profile-dropdown">
                        <button class="profile-btn"><?php echo htmlspecialchars($user_name); ?> <span class="dropdown-arrow">▼</span></button>
                        <div class="profile-menu">
                            <a href="profile.php">View Profile</a>
                            <a href="php/logout.php">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : ''; ?>">Login</a>
                    <a href="register.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'register.php' ? 'active' : ''; ?>">Register</a>
                    <?php endif; ?>
                    <a href="cart.php" class="cart-icon">
                    <img src="./images/cart-icon.png" alt="Phone Icon">
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-count"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </nav>
    </header>
</body>
</html>