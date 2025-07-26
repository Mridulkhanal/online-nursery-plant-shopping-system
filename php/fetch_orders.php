<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    exit(json_encode(['error' => 'Unauthorized']));
}
include 'db_connect.php';

$sql = "SELECT 
            o.id, 
            u.name AS customer_name, 
            o.total_amount, 
            o.status, 
            o.order_date,
            o.delivery_address,
            GROUP_CONCAT(CONCAT(p.name, ' (', oi.quantity, ')') SEPARATOR ', ') AS products,
            SUM(oi.quantity) AS total_quantity
        FROM orders o
        JOIN users u ON o.user_id = u.id
        LEFT JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN products p ON oi.product_id = p.id
        GROUP BY o.id, u.name, o.total_amount, o.status, o.order_date, o.delivery_address
        ORDER BY o.id DESC";
$result = $conn->query($sql);
$orders = [];
while ($row = $result->fetch_assoc()) {
    $status = ucfirst(strtolower($row['status'] ?? 'Pending'));
    $orders[] = [
        'id' => $row['id'],
        'customer_name' => $row['customer_name'],
        'total_amount' => $row['total_amount'],
        'status' => $row['status'],
        'order_date' => $row['order_date'],
        'delivery_address' => $row['delivery_address'] ?: 'No address',
        'products' => $row['products'] ?: 'No items',
        'total_quantity' => $row['total_quantity'] ?: 0
    ];
}
$conn->close();

header('Content-Type: application/json');
echo json_encode($orders);
?>