<?php
session_start();
require_once 'php/db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['pidx']) || !isset($_GET['order_id'])) {
    $_SESSION['error'] = "Invalid payment callback.";
    header("Location: checkout.php");
    exit();
}

$order_id = (int)$_GET['order_id'];
$pidx = filter_var($_GET['pidx'], FILTER_SANITIZE_STRING);
$secret_key = "fd93c0902cea4b208b338fb96ed072db"; // TODO: Store in environment variable

// Verify payment with Khalti
$payload = ['pidx' => $pidx];
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://dev.khalti.com/api/v2/epayment/lookup/",
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Key $secret_key",
        "Content-Type: application/json"
    ]
]);

$response = curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$curl_error = curl_error($curl);
curl_close($curl);

// Log for debugging
file_put_contents('khalti_verify.log', "HTTP Code: $http_code\nResponse: $response\ncURL Error: $curl_error\n", FILE_APPEND);

if ($response === false || !empty($curl_error)) {
    $_SESSION['error'] = "Payment verification failed: cURL error.";
    header("Location: checkout.php");
    exit();
}

$response_data = json_decode($response, true);
if ($http_code !== 200 || $response_data['status'] !== 'Completed') {
    $_SESSION['error'] = "Payment verification failed: Invalid status.";
    header("Location: checkout.php");
    exit();
}

// Store transaction
try {
    $amount = $response_data['total_amount'] / 100; // Convert paisa to NPR
    $status = $response_data['status'];
    $sql = "INSERT INTO transactions (order_id, pidx, amount, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("isds", $order_id, $pidx, $amount, $status);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    $stmt->close();
} catch (Exception $e) {
    $_SESSION['error'] = "Transaction storage failed: " . $e->getMessage();
    header("Location: checkout.php");
    exit();
}

// Update order status and log for debugging
try {
    $sql = "UPDATE orders SET status = 'paid' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $order_id);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    $stmt->close();
    // Log status update
    file_put_contents('debug.log', "Order $order_id status updated to 'paid'\n", FILE_APPEND);
} catch (Exception $e) {
    $_SESSION['error'] = "Order status update failed: " . $e->getMessage();
    header("Location: checkout.php");
    exit();
}

// Generate invoice
$order_details = $_SESSION['order_details'] ?? null;
if (!$order_details || $order_details['order_id'] !== $order_id) {
    $_SESSION['error'] = "Order details not found.";
    header("Location: checkout.php");
    exit();
}

// Fetch order status for invoice
try {
    $sql = "SELECT status FROM orders WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $order_status = $order['status'] ?? 'Unknown';
    $stmt->close();
} catch (Exception $e) {
    $order_status = 'Unknown';
    file_put_contents('debug.log', "Failed to fetch order status: " . $e->getMessage() . "\n", FILE_APPEND);
}

$invoice_number = "INV-" . str_pad($order_id, 6, "0", STR_PAD_LEFT);
$invoice_html = '
    <div class="invoice" style="background: #fff; padding: 20px; border: 1px solid #ddd; margin: 1em auto; max-width: 600px;">
        <h2 style="color: #28a745; text-align: center;">Invoice #' . $invoice_number . '</h2>
        <p style="text-align: center; color: #666;">Online Nursery System</p>
        <p style="text-align: center; color: #666;">Date: ' . date('Y-m-d') . '</p>
        <div style="margin: 1em 0;">
            <h3 style="color: #28a745;">Customer Details</h3>
            <p><strong>Name:</strong> ' . htmlspecialchars($order_details['user']['name'] ?? 'Unknown') . '</p>
            <p><strong>Email:</strong> ' . htmlspecialchars($order_details['user']['email'] ?? 'N/A') . '</p>
            <p><strong>Delivery Address:</strong> ' . htmlspecialchars($order_details['delivery_address']) . '</p>
            <p><strong>Order Status:</strong> ' . htmlspecialchars(ucfirst($order_status)) . '</p>
        </div>
        <h3 style="color: #28a745;">Order Details</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 1em;">
            <thead>
                <tr style="background: #28a745;">
                    <th style="border: 1px solid #ddd; padding: 8px;">Product</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Quantity</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Price (Rs.)</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Subtotal (Rs.)</th>
                </tr>
            </thead>
            <tbody>';
foreach ($order_details['cart_items'] as $item) {
    $invoice_html .= '
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($item['name']) . '</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">' . $item['quantity'] . '</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">' . number_format($item['price'], 2) . '</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">' . number_format($item['subtotal'], 2) . '</td>
                </tr>';
}
$invoice_html .= '
            </tbody>
        </table>
        <div style="text-align: right; margin-bottom: 1em;">
            <p><strong>Subtotal:</strong> Rs. ' . number_format($order_details['subtotal'], 2) . '</p>
            <p><strong>Shipping:</strong> Rs. ' . number_format($order_details['shipping'], 2) . '</p>
            <p><strong>Total:</strong> Rs. ' . number_format($order_details['total'], 2) . '</p>
        </div>
    </div>';

// Store invoice in database
try {
    $sql = "INSERT INTO invoices (order_id, user_id, invoice_number, invoice_html) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("iiss", $order_id, $_SESSION['user_id'], $invoice_number, $invoice_html);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    $stmt->close();
} catch (Exception $e) {
    $_SESSION['error'] = "Invoice storage failed: " . $e->getMessage();
    header("Location: checkout.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - Online Nursery</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/customer.css">
    <link rel="stylesheet" href="css/forms.css">
</head>
<body>
    <?php include 'php/header.php'; ?>
    <section class="checkout" style="background:rgb(218, 254, 218); color: #28a745; text-align: center; padding: 2em;">
        <h1>Payment Successful!</h1>
        <img src="images/checkmark.png" alt="Checkmark" style="width: 100px; margin: 1em auto;">
        <p>Your order has been confirmed by the vendor. Thank you for giving us the opportunity to serve you.</p>
        <?php echo $invoice_html; ?>
        <a href="order_confirmed.php" class="place-order" style="background: #28a745; margin-top: 1em; display: inline-block;">View Order Confirmation</a>
    </section>
    <footer>
        <p>© 2025 Online Nursery System. All rights reserved.</p>
    </footer>
</body>
</html>
<?php
$_SESSION['order_id'] = $order_id; // Store for order_confirmed.php
?>