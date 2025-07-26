<!-- index.php: Homepage for the Online Nursery System -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Nursery System - Home</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/customer.css">
    <link rel="stylesheet" href="css/forms.css"> <!-- For forms -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- For icons -->
    <script src="js/customer.js"></script>
    <script src="js/validation.js"></script> <!-- For login/register/profile -->
</head>
<body>
    <!-- Header-Mridul -->
    <?php include 'php/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero bg2">
        <h1>Welcome to Our Online Nursery</h1>
        <p>Explore a wide variety of plants, flowers, and gardening tools!</p>
        <a href="products.php" class="btn">Shop Now</a>
    </section>

    <!-- Featured Products -->
    <section class="featured-products">
        <h2>Featured Plants</h2>
        <div class="product-grid">
            <?php
            include 'php/db_connect.php';
            $sql = "SELECT * FROM products WHERE is_featured = 1 LIMIT 4";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product-card">';
                    echo '<img src="images/product_images/' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                    echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                    echo '<p>Price: Rs.' . number_format($row['price'], 2) . '</p>';
                    echo '<a href="php/customer/cart_handler.php?action=add&product_id=' . $row['id'] . '" class="btn">Add to Cart</a>';
                    echo '</div>';
                }
            } else {
                echo '<p>No featured products available.</p>';
            }
            $conn->close();
            ?>
        </div>
    </section>

    <!-- Key Features Section -->
    <div class="features">
        <div class="feature">
            <i class="fas fa-truck"></i>
            <h3>SHIPPING</h3>
            <p>Shipping on all over the Country</p>
        </div>
        <div class="feature">
            <i class="fas fa-dollar-sign"></i>
            <h3>QUALITY PRODUCTS</h3>
            <p>Your Trust is our Responsibility</p>
        </div>
        <div class="feature">
            <i class="fas fa-comments"></i>
            <h3>ONLINE SUPPORT</h3>
            <p>We support online 24/7 on day</p>
        </div>
        <div class="feature">
            <i class="fas fa-credit-card"></i>
            <h3>SECURE PAYMENTS</h3>
            <p>All payment are Secured and trusted.</p>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2025 Online Nursery System. All rights reserved.</p>
    </footer>
</body>
</html>