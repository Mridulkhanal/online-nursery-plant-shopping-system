<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}
include '../php/db_connect.php';
if (!isset($conn)) {
    die("Error: Database connection failed. Please check db_connect.php.");
}

// Fetch admin name
$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $admin_name = $user['name'] ?? 'Admin';
    $stmt->close();
} else {
    $admin_name = 'Admin';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Online Nursery</title>
    <link rel="stylesheet" href="../css/admin.css">
    <script src="../js

/admin.js"></script>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Online Nursery</h2>
        </div>
        <nav>
            <a href="admin_dashboard.php"><span class="icon">🏠</span> Dashboard</a>
            <a href="admin_products.php" class="active"><span class="icon">🌱</span> Manage Products</a>
            <a href="admin_orders.php"><span class="icon">📦</span> Manage Orders</a>
            <a href="admin_inquiries.php"><span class="icon">📩</span> View Inquiries</a>
            <a href="../php/logout.php"><span class="icon">🚪</span> Logout</a>
        </nav>
        <div class="theme-switcher"><span class="icon">🌙</span> <span>Switch Theme</span></div>
    </div>
    <div class="main-content">
        <div class="welcome-message">
            <h2>Hello, <?php echo htmlspecialchars($admin_name); ?>!</h2>
        </div>
        <h1>Manage Products</h1>
        <a href="add_product.php" style="display: inline-block; background: var(--primary); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; margin-bottom: 1em;">Add New Product</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id, name, price, stock FROM products";
                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>Rs." . number_format($row['price'], 2) . "</td>";
                        echo "<td>" . htmlspecialchars($row['stock']) . "</td>";
                        echo "<td><a href='edit_product.php?id=" . htmlspecialchars($row['id']) . "'>Edit</a> | <a href='delete_product.php?id=" . htmlspecialchars($row['id']) . "' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No products found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php $conn->close(); ?>
</body>
</html>