<!-- register.php: Customer Registration -->
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Online Nursery</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/customer.css">
    <link rel="stylesheet" href="css/forms.css"> <!-- For forms -->
    <script src="js/customer.js"></script>
    <script src="js/validation.js"></script> <!-- For login/register/profile -->
</head>
<body>
    <?php include 'php/header.php'; ?>
    <section class="register">
        <h1>Register</h1>
        <form action="php/user_auth.php" method="POST" onsubmit="return validateRegisterForm()">
            <input type="hidden" name="action" value="register">
            <label>Name:</label>
            <input type="text" name="name" required>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <label> Confirm Password:</label>
            <input type="password" name="confirm_password" required>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p style='color:red'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "<p style='color:green'>" . $_SESSION['success'] . "</p>";
            unset($_SESSION['success']);
        }
        ?>
    </section>
    <footer>
        <p>© 2025 Online Nursery System. All rights reserved.</p>
    </footer>
</body>
</html>