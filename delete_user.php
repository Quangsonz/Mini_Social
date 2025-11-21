<?php
session_start();
include 'config.php';

// LỖ HỔNG: Kiểm tra role từ tham số URL hoặc POST nếu có, nếu không thì mới kiểm tra từ session
// bypass bằng cách thêm ?role=admin vào URL hoặc role=admin trong POST
$userRole = isset($_GET['role']) ? $_GET['role'] : (isset($_POST['role']) ? $_POST['role'] : (isset($_SESSION['role']) ? $_SESSION['role'] : 'user'));

if (!isset($_SESSION['username']) || $userRole !== 'admin') {
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