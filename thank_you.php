<?php
// Start the session if needed (optional, for user data)
session_start();

// Basic security: Sanitize any input if passed (e.g., user's name from form)
$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Online Nursery</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/customer.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f9f4;
            color: #333;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2e7d32;
        }
        p {
            font-size: 1.1em;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #4caf50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #388e3c;
        }
        .footer {
            margin-top: 30px;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>
    <?php include 'php/header.php'; ?>
    <div class="container">
        <h1>Thank You, <?php echo $name; ?>!</h1>
        <p>We have received your message and appreciate you reaching out to Online Nursery.</p>
        <p>Our team will review your inquiry and get back to you within 24-48 hours.</p>
        <a href="index.php" class="btn">Return to Home</a>
    </div>
    <footer>
        <p>© 2025 Online Nursery System. All rights reserved.</p>
    </footer>
</body>
</html>