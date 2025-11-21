<?php
session_start();
require_once 'config.php';

// LỖ HỔNG: Chỉ kiểm tra đã đăng nhập (username) nhưng KHÔNG kiểm tra 2FA
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$username = $_SESSION['username'];
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="galaxy-bg">
        <div class="circle blue1"></div>
        <div class="circle blue2"></div>
        <div class="circle orange1"></div>
        <div class="circle orange2"></div>
    </div>
    <div class="home-glass" style="max-width:760px;">
        <h2 style="color:#fff; margin-top:0;">Xin chào, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></h2>
        <p>Vai trò: <strong><?php echo htmlspecialchars($role, ENT_QUOTES, 'UTF-8'); ?></strong></p>
        <div style="margin-top:15px;">
            <a class="btn" href="home.php" style="width:auto; padding:6px 16px;">Home</a>
            <a class="btn" href="index.php" style="width:auto; padding:6px 16px; margin-left:8px;">Logout</a>
        </div>
        <?php if (isset($_SESSION['2fa_verified']) && $_SESSION['2fa_verified'] !== true): ?>
            <div class="alert alert-warning" style="margin-top:15px;">Cảnh báo: Bạn chưa xác minh 2FA!</div>
        <?php endif; ?>
    </div>
</body>
</html>


