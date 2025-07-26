<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../admin_login.php");
    exit();
}
include '../php/db_connect.php';

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No inquiry ID provided.";
    header("Location: admin_inquiries.php");
    exit();
}

$inquiry_id = $_GET['id'];
$sql = "SELECT id, name, email, subject, message, response FROM inquiries WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $inquiry_id);
$stmt->execute();
$result = $stmt->get_result();
$inquiry = $result->fetch_assoc();
$stmt->close();

if (!$inquiry) {
    $_SESSION['error'] = "Inquiry not found.";
    header("Location: admin_inquiries.php");
    exit();
}

// Fetch admin name for welcome message
$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$admin_name = $user['name'] ?: 'Admin';
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $_POST['response'];
    $sql = "UPDATE inquiries SET response = ?, responded_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $response, $inquiry_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Response sent successfully!";
        header("Location: admin_inquiries.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to send response: " . $conn->error;
        error_log("Error updating inquiry response: " . $conn->error);
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respond to Inquiry - Online Nursery</title>
    <link rel="stylesheet" href="../css/admin.css">
    <script src="../js/admin.js"></script>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Online Nursery</h2>
        </div>
        <nav>
            <a href="admin_dashboard.php"><span class="icon">🏠</span> Dashboard</a>
            <a href="admin_products.php"><span class="icon">🌱</span> Manage Products</a>
            <a href="admin_orders.php"><span class="icon">📦</span> Manage Orders</a>
            <a href="admin_inquiries.php" class="active"><span class="icon">📩</span> View Inquiries</a>
            <a href="../php/logout.php"><span class="icon">🚪</span> Logout</a>
        </nav>
        <div class="theme-switcher">Switch Theme</div>
    </div>
    <div class="main-content">
        <div class="welcome-message">
            <h2>Hello, <?php echo htmlspecialchars($admin_name); ?>!</h2>
        </div>
        <h1>Respond to Inquiry</h1>
        <div class="inquiry-details">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($inquiry['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($inquiry['email']); ?></p>
        <p><strong>Subject:</strong> <?php echo htmlspecialchars($inquiry['subject']); ?></p>
        <p class="message"><strong>Message:</strong> <?php echo htmlspecialchars($inquiry['message']); ?></p>
        </div>
        <form action="" method="POST">
        <label for="response">Response:</label>
        <textarea name="response" id="response" required></textarea>
        <button type="submit">Send Response</button>
         </form>
    <?php
    if (isset($_SESSION['error'])) {
        echo "<p class='error-message'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>
</div>
</body>
</html>
<?php $conn->close(); ?>