<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../admin_login.php");
    exit();
}
include '../php/db_connect.php';

// Fetch admin name
$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$admin_name = $user['name'] ?: 'Admin';
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Online Nursery</title>
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
            <a href="admin_products.php"><span class="icon">🌱</span> Manage Products</a>
            <a href="admin_orders.php" class="active"><span class="icon">📦</span> Manage Orders</a>
            <a href="admin_inquiries.php"><span class="icon">📩</span> View Inquiries</a>
            <a href="../php/logout.php"><span class="icon">🚪</span> Logout</a>
        </nav>
        <div class="theme-switcher"><span class="icon">🌙</span> <span>Switch Theme</span></div>
    </div>
    <div class="main-content">
        <div class="welcome-message">
            <h2>Hello, <?php echo htmlspecialchars($admin_name); ?>!</h2>
        </div>
        <h1>Manage Orders</h1>
        <table id="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Products</th>
                    <th>Quantity</th>
                    <th>Address</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT 
                            o.id, 
                            o.total_amount, 
                            o.status, 
                            o.delivery_address,
                            u.name AS customer_name,
                            GROUP_CONCAT(CONCAT(p.name, ' (', oi.quantity, ')') SEPARATOR ', ') AS products,
                            SUM(oi.quantity) AS total_quantity
                        FROM orders o
                        JOIN users u ON o.user_id = u.id
                        LEFT JOIN order_items oi ON o.id = oi.order_id
                        LEFT JOIN products p ON oi.product_id = p.id
                        GROUP BY o.id, o.total_amount, o.status, o.delivery_address, u.name
                        ORDER BY o.id DESC";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $status = ucfirst(strtolower($row['status'] ?? 'Pending'));
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                    echo "<td>" . (htmlspecialchars($row['products']) ?: 'No items') . "</td>";
                    echo "<td>" . ($row['total_quantity'] ?: '0') . "</td>";
                    echo "<td>" . (htmlspecialchars($row['delivery_address']) ?: 'No address') . "</td>";
                    echo "<td>Rs." . number_format($row['total_amount'], 2) . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>";
                    if ($row['status'] != 'Delivered') {
                        $next_status = ($row['status'] == 'Pending') ? 'Shipped' : 'Delivered';
                        echo "<a href='update_order.php?order_id=" . $row['id'] . "&status=" . $next_status . "'>Mark as $next_status</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php $conn->close(); ?>
</body>
</html>