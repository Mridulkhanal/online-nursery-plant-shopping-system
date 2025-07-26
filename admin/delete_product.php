<!-- admin/delete_product.php: Delete Product -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../admin_login.php");
    exit();
}
include '../php/db_connect.php';

$product_id = $_GET['id'];
$sql = "DELETE FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
if ($stmt->execute()) {
    $_SESSION['success'] = "Product deleted successfully!";
} else {
    $_SESSION['error'] = "Failed to delete product.";
}
$stmt->close();
$conn->close();
header("Location: admin_products.php");
?>
