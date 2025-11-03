<?php

session_start();
require_once 'config.php';

// Kiểm tra đăng nhập
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if(!isset($_GET['id'])) {
    header("Location: home.php");
    exit();
}

$post_id = $_GET['id'];

// Kiểm tra xem bài viết có tồn tại không
$stmt = $config->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if(!$post) {
    $_SESSION['error'] = "Bài viết không tồn tại! ID: " . $post_id;
    header("Location: home.php");
    exit();
}

// Thực hiện xóa bài viết
if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    $stmt = $config->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    if($stmt->execute()) {
        $_SESSION['success'] = "Đã xóa bài viết thành công!";
    } else {
        $_SESSION['error'] = "Không thể xóa bài viết! Lỗi: " . $stmt->error;
    }
    $stmt->close();
    $config->close();
}

header("Location: home.php");
exit();