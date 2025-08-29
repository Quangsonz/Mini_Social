<?php
session_start();
include 'config.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    // Cập nhật thông tin người dùng
    $sql = "UPDATE users SET username = ?, role = ? WHERE id = ?";
    $stmt = $config->prepare($sql);
    $stmt->bind_param("ssi", $username, $role, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Cập nhật người dùng thành công!";
    } else {
        $_SESSION['error'] = "Lỗi khi cập nhật người dùng!";
    }

    $stmt->close();
    header('Location: user_manage.php');
    exit();
}
?> 