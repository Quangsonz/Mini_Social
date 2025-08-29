<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['username'])){
    header("Location: index.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['content'])){
    $post_id = $_POST['id'];
    $content = trim($_POST['content']);

    // Lấy thông tin bài viết
    $stmt = $config->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();

    if(!$post){
        $_SESSION['error'] = "Bài viết không tồn tại!";
        header("Location: home.php");
        exit();
    }

    if(empty($content)){
        $_SESSION['error'] = "Nội dung không được để trống!";
    } else {
        $stmt = $config->prepare("UPDATE posts SET content = ? WHERE id = ?");
        $stmt->bind_param("si", $content, $post_id);
        if($stmt->execute()){
            $_SESSION['success'] = "Sửa bài viết thành công!";
        } else {
            $_SESSION['error'] = "Không thể sửa bài viết!";
        }
    }
}
header("Location: home.php");
exit();
?>