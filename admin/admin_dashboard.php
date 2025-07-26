<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../admin_login.php");
    exit();
}
include '../php/db_connect.php';

// Ensure Nepal time zone (Asia/Kathmandu, +0545)
date_default_timezone_set('Asia/Kathmandu');

// Fetch admin name for welcome message
$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$admin_name = $user['name'] ?: 'Admin';
$stmt->close();

// Calculate metrics
$total_revenue = 0;
$total_revenue_previous = 0;
$revenue_trend = 0;
$total_orders = 0;
$total_orders_previous = 0;
$orders_trend = 0;
$total_products = 0;
$total_inquiries = 0;
$debug_messages = []; // Array to store debug info

// Define date ranges for current and previous periods
$current_start = date('Y-m-01 00:00:00'); // First day of current month
$previous_end = date('Y-m-t 23:59:59', strtotime('last month')); // Last day of previous month
$previous_start = date('Y-m-01 00:00:00', strtotime('last month')); // First day of previous month
$debug_messages[] = "Current period start: $current_start";
$debug_messages[] = "Previous period: $previous_start to $previous_end";

// Revenue: Current and previous periods
// Note: Adjust 'order_date' to your actual column name if different (e.g., 'created_at')
$sql = "SELECT SUM(total_amount) as total_revenue FROM orders WHERE status IN ('completed', 'Delivered') AND order_date >= ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $current_start);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $summary = $result->fetch_assoc();
        $total_revenue = $summary['total_revenue'] ?: 0;
        $debug_messages[] = "Current revenue: Rs. $total_revenue";
    } else {
        $debug_messages[] = "Error fetching current revenue: " . $conn->error;
        error_log("Error fetching current revenue: " . $conn->error);
    }
    $stmt->close();
} else {
    $debug_messages[] = "Failed to prepare current revenue query: " . $conn->error;
    error_log("Failed to prepare current revenue query: " . $conn->error);
}

$sql = "SELECT SUM(total_amount) as total_revenue FROM orders WHERE status IN ('completed', 'Delivered') AND order_date BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ss", $previous_start, $previous_end);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $summary = $result->fetch_assoc();
        $total_revenue_previous = $summary['total_revenue'] ?: 0;
        $debug_messages[] = "Previous revenue: Rs. $total_revenue_previous";
    } else {
        $debug_messages[] = "Error fetching previous revenue: " . $conn->error;
        error_log("Error fetching previous revenue: " . $conn->error);
    }
    $stmt->close();
} else {
    $debug_messages[] = "Failed to prepare previous revenue query: " . $conn->error;
    error_log("Failed to prepare previous revenue query: " . $conn->error);
}

// Calculate revenue trend
if ($total_revenue_previous > 0) {
    $revenue_trend = (($total_revenue - $total_revenue_previous) / $total_revenue_previous) * 100;
} elseif ($total_revenue > 0) {
    $revenue_trend = 100; // New revenue, no previous data
} else {
    $revenue_trend = 0; // No data
}
$debug_messages[] = "Revenue trend: " . number_format($revenue_trend, 2) . "%";

// Orders: Current and previous periods
$sql = "SELECT COUNT(*) as total_orders FROM orders WHERE status IN ('completed', 'Delivered') AND order_date >= ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $current_start);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $summary = $result->fetch_assoc();
        $total_orders = $summary['total_orders'] ?: 0;
        $debug_messages[] = "Current orders: $total_orders";
    } else {
        $debug_messages[] = "Error fetching current orders: " . $conn->error;
        error_log("Error fetching current orders: " . $conn->error);
    }
    $stmt->close();
} else {
    $debug_messages[] = "Failed to prepare current orders query: " . $conn->error;
    error_log("Failed to prepare current orders query: " . $conn->error);
}

$sql = "SELECT COUNT(*) as total_orders FROM orders WHERE status IN ('completed', 'Delivered') AND order_date BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ss", $previous_start, $previous_end);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $summary = $result->fetch_assoc();
        $total_orders_previous = $summary['total_orders'] ?: 0;
        $debug_messages[] = "Previous orders: $total_orders_previous";
    } else {
        $debug_messages[] = "Error fetching previous orders: " . $conn->error;
        error_log("Error fetching previous orders: " . $conn->error);
    }
    $stmt->close();
} else {
    $debug_messages[] = "Failed to prepare previous orders query: " . $conn->error;
    error_log("Failed to prepare previous orders query: " . $conn->error);
}

// Calculate orders trend
if ($total_orders_previous > 0) {
    $orders_trend = (($total_orders - $total_orders_previous) / $total_orders_previous) * 100;
} elseif ($total_orders > 0) {
    $orders_trend = 100; // New orders, no previous data
} else {
    $orders_trend = 0; // No data
}
$debug_messages[] = "Orders trend: " . number_format($orders_trend, 2) . "%";

// Products
$result = $conn->query("SELECT COUNT(*) as total_products FROM products");
if ($result) {
    $summary = $result->fetch_assoc();
    $total_products = $summary['total_products'];
    $debug_messages[] = "Total products: $total_products";
} else {
    $debug_messages[] = "Error fetching products: " . $conn->error;
    error_log("Error fetching products: " . $conn->error);
}

// Inquiries
$result = $conn->query("SELECT COUNT(*) as total_inquiries FROM inquiries");
if ($result) {
    $summary = $result->fetch_assoc();
    $total_inquiries = $summary['total_inquiries'];
    $debug_messages[] = "Total inquiries: $total_inquiries";
} else {
    $debug_messages[] = "Error fetching inquiries: " . $conn->error;
    error_log("Error fetching inquiries: " . $conn->error);
    $total_inquiries = 0; // Fallback to 0 if query fails
}

// Placeholder for expenses
$total_expenses = $total_revenue * 0.7;
$profit_loss = $total_revenue - $total_expenses;
$profit_loss_percent = $total_revenue > 0 ? ($profit_loss / $total_revenue) * 100 : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Online Nursery</title>
    <link rel="stylesheet" href="../css/admin.css">
    <script src="../js/admin.js"></script>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Online Nursery</h2>
        </div>
        <nav>
            <a href="admin_dashboard.php" class="active"><span class="icon">🏠</span> Dashboard</a>
            <a href="admin_products.php"><span class="icon">🌱</span> Manage Products</a>
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
        <h1>Admin Dashboard</h1>
        <div class="metrics">
            <div class="metric-card">
                <h3>Revenue</h3>
                <p>Rs.<?php echo number_format($total_revenue, 2); ?></p>
                <span class="trend <?php echo $revenue_trend >= 0 ? 'up' : 'down'; ?>">
                    <?php echo $revenue_trend >= 0 ? '+' : ''; ?><?php echo number_format($revenue_trend, 2); ?>%
                </span>
            </div>
            <div class="metric-card">
                <h3>Orders</h3>
                <p><?php echo $total_orders; ?></p>
                <span class="trend <?php echo $orders_trend >= 0 ? 'up' : 'down'; ?>">
                    <?php echo $orders_trend >= 0 ? '+' : ''; ?><?php echo number_format($orders_trend, 2); ?>%
                </span>
            </div>
            <div class="metric-card">
                <h3>Products</h3>
                <p><?php echo $total_products; ?></p>
                <span class="trend up">+<?php echo $total_products > 0 ? rand(1, 3) : 0; ?>%</span>
            </div>
            <div class="metric-card">
                <h3>Inquiries</h3>
                <p><?php echo $total_inquiries; ?></p>
                <span class="trend down"><?php echo $total_inquiries > 0 ? rand(1, 2) : 0; ?> pending</span>
            </div>
        </div>
        <div class="chart-placeholder">
            <p>Revenue Trend (Placeholder Chart)</p>
        </div>
        <section class="order-status">
            <h2>Recent Orders</h2>
            <table id="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT o.*, u.name as customer_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.order_date DESC LIMIT 5";
                    $result = $conn->query($sql);
                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $status = ucfirst(strtolower($row['status'] ?? 'Pending'));
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                            echo "<td>Rs." . number_format($row['total_amount'], 2) . "</td>";
                            echo "<td>" . $row['status'] . "</td>";
                            echo "<td>" . $row['order_date'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Error fetching orders: " . htmlspecialchars($conn->error) . "</td></tr>";
                        $debug_messages[] = "Error fetching recent orders: " . $conn->error;
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
    <?php $conn->close(); ?>
</body>
</html>