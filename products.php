<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'php/db_connect.php';
if (!isset($conn)) {
    die("Error: Database connection failed. Please check db_connect.php.");
}
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Online Nursery</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/customer.css">
    <script src="js/customer.js"></script>
</head>
<body>
    <?php include 'php/header.php'; ?>
    <section class="products">
        <h1>Our Products</h1>
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
        <form action="products.php" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search plants..." value="<?php echo htmlspecialchars($search); ?>">
            <select name="category_id">
                <option value="">All Categories</option>
                <?php
                $sql = "SELECT id, name FROM categories";
                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $selected = ($row['id'] === $category_id) ? 'selected' : '';
                        echo "<option value='{$row['id']}' $selected>" . htmlspecialchars($row['name']) . "</option>";
                    }
                }
                ?>
            </select>
            <button type="submit">Search</button>
        </form>
        <div class="product-grid">
            <?php
            $sql = "SELECT id, name, description, price, image_url, stock FROM products WHERE (name LIKE ? OR description LIKE ?)";
            $params = ["%$search%", "%$search%"];
            $types = "ss";
            if ($category_id) {
                $sql .= " AND category_id = ?";
                $params[] = $category_id;
                $types .= "i";
            }
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="product-card">';
                        echo '<img src="images/product_images/' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                        echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                        echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                        echo '<p class="price">Price: Rs.' . number_format($row['price'], 2) . '</p>';
                        if ($row['stock'] > 0) {
                            echo '<form class="add-to-cart-form" data-product-id="' . htmlspecialchars($row['id']) . '">';
                            echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($row['id']) . '">';
                            echo '<div class="quantity-selector">';
                            echo '<label for="quantity-' . htmlspecialchars($row['id']) . '">Quantity:</label>';
                            echo '<input type="number" name="quantity" id="quantity-' . htmlspecialchars($row['id']) . '" value="1" min="1" max="' . (int)$row['stock'] . '" data-stock="' . (int)$row['stock'] . '">';
                            echo '</div>';
                            echo '<button type="submit" class="btn add-to-cart-btn">Add to Cart</button>';
                            echo '</form>';
                        } else {
                            echo '<p class="out-of-stock">Out of Stock</p>';
                        }
                        echo '<a href="product_details.php?id=' . htmlspecialchars($row['id']) . '" class="btn view-details-btn">View Details</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No products found.</p>';
                }
                $stmt->close();
            } else {
                echo '<p class="error-message">Database error occurred.</p>';
            }
            ?>
        </div>
    </section>
    <footer>
        <p>© 2025 Online Nursery System. All rights reserved.</p>
    </footer>
    <?php $conn->close(); ?>
</body>
</html>