<!-- admin/update_order.php: Update Order Status -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../admin_login.php");
    exit();
}
include '../php/db_connect.php';

$order_id = $_GET['order_id'] ?? null;
$status = $_GET['status'] ?? null;

// Validate status (case-insensitive)
$valid_statuses = ['pending', 'shipped', 'delivered'];
$normalized_status = ucfirst(strtolower($status)); // Normalize to title-case
if (!$order_id || !is_numeric($order_id) || !in_array(strtolower($status), $valid_statuses)) {
    $_SESSION['error'] = "Invalid order ID or status.";
    header("Location: admin_orders.php");
    exit();
}

$sql = "UPDATE orders SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    $_SESSION['error'] = "Failed to prepare update query: " . $conn->error;
    header("Location: admin_orders.php");
    exit();
}
$stmt->bind_param("si", $normalized_status, $order_id);
if ($stmt->execute()) {
    $_SESSION['success'] = "Order status updated to $normalized_status!";
} else {
    $_SESSION['error'] = "Failed to update order status: " . $stmt->error;
}
$stmt->close();
$conn->close();

header("Location: admin_orders.php");
exit();
?>