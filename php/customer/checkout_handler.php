<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
require_once '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $payment_method = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);
    $delivery_address = filter_input(INPUT_POST, 'delivery_address', FILTER_SANITIZE_STRING);

    if (empty($delivery_address)) {
        $_SESSION['error'] = "Delivery address is required.";
        header("Location: checkout.php");
        exit();
    }

    if ($payment_method !== 'khalti') {
        $_SESSION['error'] = "Invalid payment method.";
        header("Location: checkout.php");
        exit();
    }

    // Calculate cart total and validate cart items
    $total = 0;
    $cart_items = [];
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $quantity = (int)$item['quantity'];
        $sql = "SELECT id, name, price FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $subtotal = $row['price'] * $quantity;
            $cart_items[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
            $total += $subtotal;
        }
        $stmt->close();
    }

    if (empty($cart_items)) {
        $_SESSION['error'] = "Your cart is empty.";
        header("Location: cart.php");
        exit();
    }

    $shipping = 50;
    $total += $shipping; // Add shipping cost
    $total_amount = $total * 100; // Convert to paisa for Khalti

    // Fetch user information for customer details
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT name, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        $_SESSION['error'] = "User information not found.";
        header("Location: checkout.php");
        exit();
    }

    // Start database transaction
    $conn->begin_transaction();

    try {
        // Insert order into database
        $status = 'Pending';
        $sql = "INSERT INTO orders (user_id, total_amount, status, delivery_address) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idss", $user_id, $total, $status, $delivery_address);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Insert order items
        foreach ($cart_items as $item) {
            $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $order_id, $item['id'], $item['quantity']);
            $stmt->execute();
            $stmt->close();
        }

        // Commit transaction
        $conn->commit();

        // Store order details in session for billing
        $_SESSION['order_details'] = [
            'order_id' => $order_id,
            'cart_items' => $cart_items,
            'subtotal' => $total - $shipping,
            'shipping' => $shipping,
            'total' => $total,
            'delivery_address' => $delivery_address,
            'user' => $user
        ];

        // Clear cart after successful order placement
        $_SESSION['cart'] = [];

        // Khalti payment initiation
        $secret_key = "fd93c0902cea4b208b338fb96ed072db"; // TODO: Store in environment variable
        $return_url = "http://localhost/OnlineNurserySystem/payment_successful.php?order_id=$order_id";
        $website_url = "http://localhost/OnlineNurserySystem/";
        $purchase_order_id = "Order-$order_id";
        $purchase_order_name = "Order $order_id";

        $payload = [
            "return_url" => $return_url,
            "website_url" => $website_url,
            "amount" => $total_amount,
            "purchase_order_id" => $purchase_order_id,
            "purchase_order_name" => $purchase_order_name,
            "customer_info" => [
                "name" => $user['name'] ?? "Unknown Customer",
                "email" => $user['email'] ?? "no-email@example.com",
                "phone" => "9800000000" // Fallback phone number
            ]
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://dev.khalti.com/api/v2/epayment/initiate/", // Sandbox for testing
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

        // Log Khalti response for debugging
        file_put_contents('khalti_response.log', "HTTP Code: $http_code\nResponse: $response\ncURL Error: $curl_error\n", FILE_APPEND);

        if ($response === false || !empty($curl_error)) {
            throw new Exception("cURL error: $curl_error");
        }

        $response_data = json_decode($response, true);

        if ($http_code === 200 && isset($response_data['payment_url'])) {
            header("Location: {$response_data['payment_url']}");
            exit();
        } else {
            $error_message = $response_data['error'] ?? 'Unknown error';
            throw new Exception("Khalti API error: $error_message");
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $_SESSION['error'] = "Failed to process order: " . $e->getMessage();
        header("Location: checkout.php");
        exit();
    }
}
?>