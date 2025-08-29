<?php
session_start();
require_once 'config.php';

$error = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(empty($username) || empty($password)){
        $error = "Vui lòng điền đủ thông tin!";
    }else{
        $stmt = $config -> prepare("SELECT * FROM users WHERE username = ?");
        $stmt -> bind_param("s",$username);
        $stmt -> execute();
        $result = $stmt -> get_result();
        if($row = $result -> fetch_assoc()){
            if(password_verify($password, $row['password'])){
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $row['role'];
                header("location:home.php");
                exit();
            }else{
                $error = "Sai mật khẩu!";
            }
        }else{
            $error = "Sai tên người dùng hoặc người dùng không tồn tại!";
        }
        $stmt -> close();
        $config -> close();
    }
}
include 'views/index.html'; 