<?php
session_start();
require_once 'php/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$order_id = isset($_SESSION['order_id']) ? (int)$_SESSION['order_id'] : 0;
$invoice_number = $order_id ? "INV-" . str_pad($order_id, 6, "0", STR_PAD_LEFT) : "Unknown";

// Fetch order status
$order_status = 'Unknown';
if ($order_id) {
    try {
        $sql = "SELECT status FROM orders WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $order_status = $row['status'];
        }
        $stmt->close();
    } catch (Exception $e) {
        file_put_contents('debug.log', "Failed to fetch order status in order_confirmed: " . $e->getMessage() . "\n", FILE_APPEND);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - Online Nursery</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/customer.css">
    <link rel="stylesheet" href="css/forms.css">
</head>
<body>
    <?php include 'php/header.php'; ?>
    <section class="checkout" style="background: #f0fff0; color: #28a745; text-align: center; padding: 2em;">
        <h1>Thank you, your order is confirmed.</h1>
        <img src="images/checkmark.png" alt="Checkmark" style="width: 100px; margin: 1em auto;">
        <p style="color: #666; margin: 1em 0;">Invoice Number: <?php echo htmlspecialchars($invoice_number); ?></p>
        <p style="color: #666; margin: 1em 0;">Order Status: <?php echo htmlspecialchars(ucfirst($order_status)); ?></p>
        <div style="margin-top: 1em;">
            <a href="products.php" class="continue-shopping" style="background: #ccc; color: #333;">Continue Shopping</a>
            <a href="profile.php" class="place-order" style="background: #28a745; margin-left: 1em;">View Recent Orders</a>
        </div>
        <p style="color: #666; margin-top: 1em;">Order Confirmed</p>
    </section>
</body>
</html>
<?php
// Clear session data
unset($_SESSION['order_details']);
unset($_SESSION['order_id']);
?>