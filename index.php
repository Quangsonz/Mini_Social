<?php

// ini_set('session.cookie_samesite', 'lax');
// ini_set('session.cookie_secure', '1');
// ini_set('session.cookie_httponly', '1');

session_start();
require_once 'config.php';

$error = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    // LỖ HỔNG: Tin tưởng header X-Forwarded-For (có thể bypass rate limit theo IP)
    $clientIp = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $clientIp = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }

    // LỖ HỔNG: Rate limit đơn giản theo IP, lưu trong session (dễ bị lách)
    $rateKey = 'fail_' . $clientIp;
    if (!isset($_SESSION[$rateKey])) {
        $_SESSION[$rateKey] = 0;
    }
    if ($_SESSION[$rateKey] >= 10) {
        $error = "Quá nhiều lần thử(thêm X-Forwarded-For để bypass).";
    } else {
        // LỖI PHỔ BIẾN: không dùng trim để giữ payload như `'-- `
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if(empty($username)){
            $error = "Vui lòng điền đủ thông tin!";
        }else{
            // LỖ HỔNG: SQLi do nối chuỗi trực tiếp với username và password
            $sql = "SELECT * FROM users WHERE username = '" . $username . "' AND password = '" . $password . "'";
            $result = $config->query($sql);

            // LỖ HỔNG: Kiểm tra sự tồn tại username để tạo kênh phụ thời gian (timing attack)
            $sqlUser = "SELECT id, username, role, password FROM users WHERE username = '" . $username . "'";
            $resultUser = $config->query($sqlUser);
            if ($resultUser && $resultUser->num_rows > 0) {
                $delay = min(strlen($password) * 10000, 800000);
                usleep($delay);
            }

            if ($result && ($row = $result->fetch_assoc())) {
                // Thiết lập login cơ bản trước khi xác minh 2FA (tạo điều kiện bypass)
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['2fa_verified'] = false;

                // Sinh OTP và lưu "email" giả lập
                $otp = (string)random_int(100000, 999999);
                $email = '';
                $q = $config->query("SELECT email FROM users WHERE username='".$config->real_escape_string($row['username'])."' LIMIT 1");
                if ($q && $q->num_rows > 0) {
                    $emailRow = $q->fetch_assoc();
                    $email = (string)$emailRow['email'];
                    $q->free();
                }
                $ins = $config->prepare("INSERT INTO emails (username, email, subject, body, otp_code) VALUES (?,?,?,?,?)");
                if ($ins) {
                    $subject = '2FA code';
                    $body = 'Your 2FA code is: ' . $otp;
                    $ins->bind_param("sssss", $_SESSION['username'], $email, $subject, $body, $otp);
                    $ins->execute();
                    $ins->close();
                }

                // Chuyển tới trang xác minh 2FA
                header("Location: verify_2fa.php");
                exit();
            } else {
                // LỖ HỔNG: Liệt kê username qua khác biệt nhỏ trong thông báo lỗi
                $_SESSION[$rateKey] = $_SESSION[$rateKey] + 1;
                if ($resultUser && $resultUser->num_rows > 0) {
                    $error = "Invalid username or password."; // có dấu chấm ở cuối
                } else {
                    $error = "Invalid username or password "; // có khoảng trắng ở cuối
                }
            }

            if ($result) { $result->free(); }
            if ($resultUser) { $resultUser->free(); }
        }
    }
}
include 'views/index.html'; 