<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['hasItems' => false]);
    exit();
}

$hasItems = isset($_SESSION['cart']) && is_array($_SESSION['cart']) && !empty($_SESSION['cart']);
echo json_encode(['hasItems' => $hasItems]);
?>