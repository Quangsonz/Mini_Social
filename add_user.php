<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $config->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Thêm người dùng thành công!";
    } else {
        $_SESSION['error'] = "Lỗi khi thêm người dùng: " . $stmt->error;
    }

    $stmt->close();
    header('Location: user_manage.php');
    exit();
}
?> 