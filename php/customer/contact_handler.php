<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    // Add email sending logic or database storage here
    $_SESSION['success_message'] = "Message sent successfully!";
    header("Location: ../../contact.php");
    exit();
}
?>