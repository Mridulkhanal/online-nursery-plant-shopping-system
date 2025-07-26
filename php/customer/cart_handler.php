<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../db_connect.php';
if (!isset($conn)) {
    $_SESSION['error'] = "Database connection failed. Please check db_connect.php.";
    header("Location: ../../products.php");
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;
$referer = $_SERVER['HTTP_REFERER'] ?? '../../products.php';

if ($action === 'add' && $product_id > 0 && $quantity > 0) {
    // Validate product and stock
    $sql = "SELECT name, price, stock FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($product) {
            $available_stock = (int)$product['stock'];
            $current_quantity = isset($_SESSION['cart'][$product_id]) ? (int)$_SESSION['cart'][$product_id]['quantity'] : 0;
            $total_quantity = $current_quantity + $quantity;

            if ($total_quantity <= $available_stock) {
                if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                if (!isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id] = [
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'quantity' => $quantity
                    ];
                    $_SESSION['success'] = "Product added to cart!";
                } else {
                    $_SESSION['cart'][$product_id]['quantity'] = $total_quantity;
                    $_SESSION['success'] = "Quantity updated in cart!";
                }
            } else {
                $_SESSION['error'] = "Requested quantity exceeds available stock!";
            }
        } else {
            $_SESSION['error'] = "Product not found!";
        }
    } else {
        $_SESSION['error'] = "Database error: Unable to prepare statement!";
    }
} else {
    $_SESSION['error'] = "Invalid action or parameters!";
}

$conn->close();
header("Location: $referer");
exit();
?>