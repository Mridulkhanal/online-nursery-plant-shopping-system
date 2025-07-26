<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed - Online Nursery</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/customer.css">
    <link rel="stylesheet" href="css/forms.css">
</head>
<body>
    <?php include 'php/header.php'; ?>
    <section class="checkout" style="background: #fff8f8; color: #dc3545; text-align: center; padding: 2em;">
        <h1>Payment Failed!</h1>
        <img src="images/broken_card.png" alt="Broken Card" style="width: 100px; margin: 1em auto;">
        <p>Your card was declined by the vendor. Please select another payment method.</p>
        <a href="checkout.php" class="place-order" style="background: #dc3545; margin-top: 1em;">Back to Payment</a>
        <p style="color: #666; margin-top: 1em;">Payment Failed</p>
    </section>
</body>
</html>