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

    // Xóa tất cả bài viết của user trước
    $stmt = $config->prepare("DELETE FROM posts WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Sau đó mới xóa user
    $stmt = $config->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Xóa người dùng thành công!";
    } else {
        $_SESSION['error'] = "Lỗi khi xóa người dùng!";
    }
    $stmt->close();
    header('Location: user_manage.php');
    exit();
}
?> 