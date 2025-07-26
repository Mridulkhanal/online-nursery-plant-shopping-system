<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if ($action == 'register') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $role = 'customer';

        if ($password !== $confirm_password) {
            $_SESSION['error'] = "Passwords do not match.";
            header("Location: ../register.php");
            exit();
        }

        $password = password_hash($password, PASSWORD_DEFAULT);
        $confirm_password = password_hash($confirm_password, PASSWORD_DEFAULT);


        try {
            $sql = "INSERT INTO users (name, email, password, confirm_password, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $email, $password, $confirm_password, $role);
            $stmt->execute();
            
            $_SESSION['success'] = "Registration successful! Please login.";
            header("Location: ../login.php");
        } catch (mysqli_sql_exception $e) {
            error_log("Registration error: " . $e->getMessage() . " (Code: " . $e->getCode() . ")");
            if ($e->getCode() == 1062) {
                $_SESSION['error'] = "Email is already registered.";
            } elseif ($e->getCode() == 3819) {
                $_SESSION['error'] = "Invalid email format.";
            } else {
                $_SESSION['error'] = "Registration failed. Please try again.";
            }
            header("Location: ../register.php");
        }
        $stmt->close();
    }

    if ($action == 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                header("Location: ../index.php");
            } else {
                $_SESSION['error'] = "Invalid password.";
                header("Location: ../login.php");
            }
        } else {
            $_SESSION['error'] = "User not found.";
            header("Location: ../login.php");
        }
        $stmt->close();
    }
}

$conn->close();
?>