<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../admin_login.php");
    exit();
}
include '../php/db_connect.php';

// Fetch admin name for welcome message
$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$admin_name = $user['name'] ?: 'Admin';
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inquiries - Online Nursery</title>
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
        <h1>Manage Inquiries</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id, name, email, subject, message FROM inquiries";
                $result = $conn->query($sql);
                if ($result) {
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                            echo "<td><a href='respond_inquiry.php?id=" . $row['id'] . "'>Respond</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No inquiries found.</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Error fetching inquiries: " . htmlspecialchars($conn->error) . "</td></tr>";
                    error_log("Error fetching inquiries: " . $conn->error);
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php $conn->close(); ?>
</body>
</html>