<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../admin_login.php");
    exit();
}
include '../php/db_connect.php';

// Fetch admin name for welcome message
$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$admin_name = $user['name'] ?: 'Admin';
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'] ?: null;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    $target_dir = "../images/product_images/";
    $image_url = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_url;
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO products (name, description, price, stock, image_url, category_id, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdissi", $name, $description, $price, $stock, $image_url, $category_id, $is_featured);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Product added successfully!";
            header("Location: admin_products.php");
        } else {
            $_SESSION['error'] = "Failed to add product.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Failed to upload image.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Online Nursery</title>
    <link rel="stylesheet" href="../css/admin.css">
    <script src="../js/admin.js"></script>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Online Nursery</h2>
        </div>
        <nav>
            <a href="admin_dashboard.php"><span class="icon">🏠</span> Dashboard</a>
            <a href="admin_products.php" class="active"><span figure><span class="icon">🌱</span> Manage Products</a>
            <a href="admin_orders.php"><span class="icon">📦</span> Manage Orders</a>
            <a href="admin_inquiries.php"><span class="icon">📩</span> View Inquiries</a>
            <a href="../php/logout.php"><span class="icon">🚪</span> Logout</a>
        </nav>
        <div class="theme-switcher">Switch Theme</div>
    </div>
    <div class="main-content">
        <div class="welcome-message">
            <h2>Hello, <?php echo htmlspecialchars($admin_name); ?>!</h2>
        </div>
        <h1>Add New Product</h1>
        <form action="" method="POST" enctype="multipart/form-data" class="modern-form">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required placeholder="Enter product name">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required placeholder="Describe the product"></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price(Rs.):</label>
                <input type="number" id="price" step="0.01" name="price" required placeholder="Enter price">
            </div>
            <div class="form-group">
                <label for="stock">Stock Quantity:</label>
                <input type="number" id="stock" name="stock" required placeholder="Enter stock quantity" min="0">
            </div>
            <div class="form-group">
                <label for="category_id">Category:</label>
                <select id="category_id" name="category_id">
                    <option value="">Select Category</option>
                    <?php
                    $sql = "SELECT * FROM categories";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>" . htmlspecialchars($row['name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Product Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <div class="form-group checkbox-group">
                <label>
                    <input type="checkbox" name="is_featured"> Featured Product
                </label>
            </div>
            <button type="submit" class="form-submit">Add Product</button>
        </form>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error-message'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>