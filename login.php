<!-- login.php: Customer Login -->
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Online Nursery</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/customer.css">
    <link rel="stylesheet" href="css/forms.css"> <!-- For forms -->
    <script src="js/customer.js"></script>
    <script src="js/validation.js"></script> <!-- For login/register/profile -->
</head>
<body>
    <?php include 'php/header.php'; ?>
    <section class="login">
        <h1>Login</h1>
        <form action="php/user_auth.php" method="POST" onsubmit="return validateLoginForm()">
            <input type="hidden" name="action" value="login">
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p style='color:red'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        ?>
    </section>
    <footer>
        <p>© 2025 Online Nursery System. All rights reserved.</p>
    </footer>
</body>
</html>