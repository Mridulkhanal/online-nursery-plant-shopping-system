<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'php/db_connect.php';

// Initialize user data
$user_data = ['name' => 'N/A', 'email' => 'N/A'];

// Debug: Check database connection
if ($conn->connect_error) {
    $_SESSION['error'] = "Database connection failed: " . $conn->connect_error;
} else {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT id, name, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $_SESSION['error'] = "Database query preparation failed: " . $conn->error;
    } else {
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) {
            $_SESSION['error'] = "User query execution failed: " . $stmt->error;
        } else {
            $result = $stmt->get_result();
            $row_count = $result->num_rows;
            if ($row_count > 0) {
                $user_data = $result->fetch_assoc();
            } else {
                $_SESSION['error'] = "User not found for ID: " . htmlspecialchars($user_id) . " (Rows returned: $row_count)";
            }
        }
        $stmt->close();
    }
}

// Temporary debug output (remove after debugging)
// echo "<p style='color:blue'>Debug: User ID = " . htmlspecialchars($user_id) . ", Row Count = " . ($row_count ?? 'N/A') . ", User Data = " . htmlspecialchars(print_r($user_data, true)) . ", Session Data = " . htmlspecialchars(print_r($_SESSION, true)) . "</p>";

$total_purchased = 0;
$sql = "SELECT SUM(total_amount) as total FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    $_SESSION['error'] = "Order query preparation failed: " . $conn->error;
} else {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $total_purchased = $row['total'] ?: 0;
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $_SESSION['error'] = "Update query preparation failed: " . $conn->error;
    } else {
        $stmt->bind_param("ssi", $name, $email, $user_id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Profile updated successfully!";
            header("Location: profile.php");
        } else {
            $_SESSION['error'] = "Failed to update profile: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Online Nursery</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/customer.css">
    <link rel="stylesheet" href="css/forms.css">
    <script src="js/validation.js"></script>
</head>
<body>
    <?php include 'php/header.php'; ?>
    <section class="profile">
        <h1>Your Profile</h1>
        <div class="profile-summary">
            <h2>Account Summary</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user_data['name'] ?? 'N/A'); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_data['email'] ?? 'N/A'); ?></p>
            <p><strong>Total Purchased:</strong> NRs.<?php echo number_format($total_purchased, 2); ?></p>
        </div>
        <h2>Order History</h2>
        <table id="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>   
            <tbody>
            <?php
            $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo "<tr><td colspan='4'>Error fetching orders: " . htmlspecialchars($conn->error) . "</td></tr>";
            } else {
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows === 0) {
                    echo "<tr><td colspan='4'>No orders found.</td></tr>";
                } else {
                    while ($row = $result->fetch_assoc()) {
                        $status = ucfirst(strtolower($row['status'] ?? 'Pending'));
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td> Rs." . number_format($row['total_amount'], 2) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['order_date']) . "</td>";
                        echo "</tr>";
                    }
                }
                $stmt->close();
            }
            ?>
            </tbody> 
        </table>
        <?php
        if (isset($_SESSION['success'])) {
            echo "<p style='color:green'>" . htmlspecialchars($_SESSION['success']) . "</p>";
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo "<p style='color:red'>" . htmlspecialchars($_SESSION['error']) . "</p>";
            unset($_SESSION['error']);
        }
        ?>
    </section>
    <footer>
        <p>© 2025 Online Nursery System. All rights reserved.</p>
    </footer>
    <?php $conn->close(); ?>
</body>
</html>