<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'php/db_connect.php';

// Initialize cart if not set
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Fetch cart items from database
$cart_items = [];
$total = 0;
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
$cart_empty = empty($cart_items);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Online Nursery</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/customer.css">
    <link rel="stylesheet" href="css/forms.css">
    <script src="js/customer.js"></script>
    <script src="js/validation.js"></script>
</head>
<body>
    <?php include 'php/header.php'; ?>
    <section class="checkout checkout-split">
        <h1>Checkout</h1>
        <div class="checkout-container">
            <div class="order-summary">
                <h2>Order Summary</h2>
                <?php if ($cart_empty): ?>
                    <div class="error-message">Your cart is empty. Please add items to proceed.</div>
                    <a href="cart.php" class="btn">Return to Cart</a>
                <?php else: ?>
                    <table role="grid" aria-label="Order Summary">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td>Rs.<?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>Rs.<?php echo number_format($item['subtotal'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="order-totals">
                                <td colspan="3" style="text-align: right; font-weight: bold;">Subtotal</td>
                                <td>Rs.<?php echo number_format($total, 2); ?></td>
                            </tr>
                            <tr class="order-totals">
                                <td colspan="3" style="text-align: right; font-weight: bold;">Shipping</td>
                                <td>Rs.50.00</td>
                            </tr>
                            <tr class="order-totals">
                                <td colspan="3" style="text-align: right; font-weight: bold;">Total</td>
                                <td>Rs.<?php echo number_format($total + 50, 2); ?></td>
                            </tr>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <?php if (!$cart_empty): ?>
                <div class="payment-form">
                    <h2>Payment Details</h2>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    <form action="php/customer/checkout_handler.php" method="POST" onsubmit="return validateCheckoutForm()">
                        <input type="hidden" name="payment_method" value="khalti">
                        <label for="delivery_address">Delivery Address:</label>
                        <textarea id="delivery_address" name="delivery_address" required aria-required="true"></textarea>
                        <button type="submit" class="khalti-btn">
                            <img src="images/khalti.png" alt="Khalti"> Pay with Khalti
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php $conn->close(); ?>
</body>
</html>