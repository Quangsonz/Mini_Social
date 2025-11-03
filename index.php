<?php

ini_set('session.cookie_samesite', 'lax');
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');

session_start();
require_once 'config.php';

$error = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    // lỗi thường gặp — dùng trực tiếp đầu vào, không trim để giữ khoảng trắng sau "--"
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if(empty($username)){
        $error = "Vui lòng điền đủ thông tin!";
    }else{
        // // LỖI: nối chuỗi vào SQL 
        // $sql = "SELECT * FROM users WHERE username = '" . $username . "' AND password = '" . $password . "'";
        // $result = $config->query($sql);
        // if ($result && ($row = $result->fetch_assoc())) {
        //     session_regenerate_id(true);
        //     $_SESSION['username'] = $row['username'];
        //     $_SESSION['role'] = $row['role'];
        //     header("location:home.php");
        //     exit();
        // } else {
        //     $error = "Sai tên người dùng hoặc mật khẩu!";
        // }
        // if ($result) { $result->free(); }
        $sqlUser = "SELECT * FROM users WHERE username = '" . $username . "'";
        $resultUser = $config->query($sqlUser);

        if ($resultUser && $resultUser->num_rows > 0) {
            // User tồn tại -> kiểm tra mật khẩu
            $row = $resultUser->fetch_assoc();
            if ($password === $row['password']) {
                session_regenerate_id(true);
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                header("Location: home.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            // Không tìm thấy username
            $error = "Invalid username or password ";
        }

        if ($resultUser) { $resultUser->free(); }
    }
}
include 'views/index.html'; 