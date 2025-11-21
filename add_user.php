<?php
session_start();
include 'config.php';

// LỖ HỔNG: Kiểm tra role từ tham số URL hoặc POST nếu có, nếu không thì mới kiểm tra từ session
// Cho phép bypass bằng cách thêm ?role=admin vào URL hoặc role=admin trong POST
$userRole = isset($_GET['role']) ? $_GET['role'] : (isset($_POST['role']) ? $_POST['role'] : (isset($_SESSION['role']) ? $_SESSION['role'] : 'user'));

if (!isset($_SESSION['username']) || $userRole !== 'admin') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $config->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);

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