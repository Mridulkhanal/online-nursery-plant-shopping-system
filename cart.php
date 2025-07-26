<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'php/db_connect.php';

// Initialize cart if not set
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $product_id = (int)$_POST['product_id'];
    if ($_POST['action'] == 'update') {
        $quantity = (int)$_POST['quantity'];
        // Fetch product stock
        $sql = "SELECT stock FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $stock = (int)$row['stock'];
            if ($quantity >= 1 && $quantity <= $stock && isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
                $_SESSION['success'] = "Cart updated successfully!";
            } else {
                $_SESSION['error'] = "Invalid quantity or product not found!";
            }
        } else {
            $_SESSION['error'] = "Product not found!";
        }
        $stmt->close();
    } elseif ($_POST['action'] == 'remove') {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            $_SESSION['success'] = "Product removed from cart!";
        } else {
            $_SESSION['error'] = "Product not found in cart!";
        }
    }
    header("Location: cart.php");
    exit();
}

// Fetch cart items from database
$cart_items = [];
$total = 0;
foreach ($_SESSION['cart'] as $product_id => $item) {
    $quantity = (int)$item['quantity'];
    $sql = "SELECT id, name, price, stock FROM products WHERE id = ?";
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
            'subtotal' => $subtotal,
            'stock' => $row['stock']
        ];
        $total += $subtotal;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Online Nursery</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/customer.css">
    <script src="js/customer.js" defer></script>
</head>
<body>
    <?php include 'php/header.php'; ?>
    <section class="cart">
        <h1>Your Cart</h1>
        <?php if (empty($cart_items)): ?>
            <p class="error-message">Your cart is currently empty. <a href="products.php">Continue shopping</a></p>
        <?php else: ?>
            <?php if (isset($_SESSION['success'])): ?>
                <p class="success-message"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></p>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <p class="error-message"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
            <?php endif; ?>
            <table class="cart-table" role="grid" aria-label="Cart Items">
                <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>Rs.<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <form method="POST" class="quantity-form">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <div class="quantity-selector">
                                        <label for="quantity-<?php echo $item['id']; ?>" class="sr-only">Quantity</label>
                                        <input type="number" name="quantity" id="quantity-<?php echo $item['id']; ?>" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo (int)$item['stock']; ?>" step="1" data-stock="<?php echo (int)$item['stock']; ?>" onchange="this.form.submit()">
                                    </div>
                                    <input type="hidden" name="action" value="update">
                                </form>
                            </td>
                            <td>Rs.<?php echo number_format($item['subtotal'], 2); ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="remove-btn" aria-label="Remove <?php echo htmlspecialchars($item['name']); ?>">
                                        <img src="images/trash.svg" alt="" class="remove-icon">
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="order-totals">
                        <td colspan="3" style="text-align: right; font-weight: bold;">Subtotal</td>
                        <td colspan="2">Rs.<?php echo number_format($total, 2); ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="cart-actions">
                <a href="products.php" class="btn continue-shopping">Continue Shopping</a>
                <a href="checkout.php" class="btn place-order">Place Order</a>
            </div>
        <?php endif; ?>
    </section>
    <footer>
        <p>© 2025 Online Nursery System. All rights reserved.</p>
    </footer>
    <?php $conn->close(); ?>
</body>
</html>