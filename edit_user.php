<?php
session_start();
include 'config.php';

// LỖ HỔNG: Kiểm tra role từ tham số URL hoặc POST nếu có, nếu không thì mới kiểm tra từ session
// bypass bằng cách thêm ?role=admin vào URL hoặc role=admin trong POST
$userRole = isset($_GET['role']) ? $_GET['role'] : (isset($_POST['role']) ? $_POST['role'] : (isset($_SESSION['role']) ? $_SESSION['role'] : 'user'));

// if (!isset($_SESSION['username']) || $userRole !== 'admin') {
//     header('Location: index.php');
//     exit();
// }

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $email = isset($_POST['email']) ? $_POST['email'] : null;

    // Cập nhật thông tin người dùng
    $sql = "UPDATE users SET username = ?, role = ?, email = ? WHERE id = ?";
    $stmt = $config->prepare($sql);
    $stmt->bind_param("sssi", $username, $role, $email, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Cập nhật người dùng thành công!";
    } else {
        $_SESSION['error'] = "Lỗi khi cập nhật người dùng!". $stmt->error;
    }

    $stmt->close();
    header('Location: user_manage.php');
    exit();
}
?> 