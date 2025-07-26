<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'php/db_connect.php';
if (!isset($conn)) {
    header("Location: products.php?error=Database connection failed");
    exit();
}
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($product_id <= 0) {
    header("Location: products.php");
    exit();
}
$sql = "SELECT p.id, p.name, p.description, p.price, p.image_url, p.stock, p.category_id, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$product) {
        header("Location: products.php");
        exit();
    }
} else {
    header("Location: products.php?error=Database error");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Online Nursery</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/customer.css">
</head>
<body>
    <?php include 'php/header.php'; ?>
    <section class="product-details">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <?php
        if (isset($_SESSION['success'])) {
            echo '<p class="success-message">' . htmlspecialchars($_SESSION['success']) . '</p>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<p class="error-message">' . htmlspecialchars($_SESSION['error']) . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <img src="images/product_images/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></p>
        <p><strong>Price:</strong> Rs.<?php echo number_format($product['price'], 2); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
        <?php if ($product['stock'] > 0) { ?>
            <form class="add-to-cart-form" data-product-id="<?php echo htmlspecialchars($product['id']); ?>">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                <div class="quantity-selector">
                    <label for="quantity-<?php echo htmlspecialchars($product['id']); ?>">Quantity:</label>
                    <input type="number" name="quantity" id="quantity-<?php echo htmlspecialchars($product['id']); ?>" value="1" min="1" max="<?php echo (int)$product['stock']; ?>" step="1" data-stock="<?php echo (int)$product['stock']; ?>">
                </div>
                <button type="submit" class="btn add-to-cart-btn">Add to Cart</button>
            </form>
        <?php } else { ?>
            <p class="out-of-stock">Out of Stock</p>
        <?php } ?>
        <h2>Recommended Products</h2>
        <div class="product-grid">
            <?php
            $sql = "SELECT id, name, price, image_url FROM products WHERE category_id = ? AND id != ? LIMIT 3";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ii", $product['category_id'], $product['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="product-card">';
                        echo '<img src="images/product_images/' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                        echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                        echo '<p class="price">Price: Rs.' . number_format($row['price'], 2) . '</p>';
                        echo '<a href="product_details.php?id=' . htmlspecialchars($row['id']) . '" class="btn view-details-btn">View Details</a>';
                        echo '</div>';
                    }
                }
                $stmt->close();
            }
            ?>
        </div>
    </section>
    <footer>
        <p>© 2025 Online Nursery System. All rights reserved.</p>
    </footer>
    <?php $conn->close(); ?>
    <script src="js/customer.js" defer></script>
</body>
</html>