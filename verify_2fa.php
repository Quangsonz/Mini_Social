<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = isset($_POST['otp']) ? trim($_POST['otp']) : '';
    $username = $_SESSION['username'];

    // Lấy OTP mới nhất cho user
    $stmt = $config->prepare("SELECT otp_code FROM emails WHERE username = ? ORDER BY created_at DESC, id DESC LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($otp_code);
        if ($stmt->fetch()) {
            if ($otp !== '' && hash_equals((string)$otp_code, (string)$otp)) {
                $_SESSION['2fa_verified'] = true;
                header('Location: my_account.php');
                exit();
            } else {
                $error = 'Mã OTP không đúng!';
            }
        } else {
            $error = 'Không tìm thấy OTP. Hãy thử đăng nhập lại.';
        }
        $stmt->close();
    } else {
        $error = 'Lỗi hệ thống.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác minh 2FA</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="galaxy-bg">
        <div class="circle blue1"></div>
        <div class="circle blue2"></div>
        <div class="circle orange1"></div>
        <div class="circle orange2"></div>
    </div>
    <div class="register-glass" style="max-width:480px;">
        <h2 style="color:#fff;">Nhập mã xác minh 2FA</h2>
        <form method="post" action="verify_2fa.php" autocomplete="off">
            <div class="form-group">
                <span class="input-icon"><i class="fa fa-key"></i></span>
                <input type="text" name="otp" placeholder="Mã OTP" required>
            </div>
            <button type="submit" class="btn">Xác minh</button>
            <a class="btn" style="margin-left:10px; width:auto; padding:5px 16px; display:inline-block;" href="client_email.php?username=<?php echo urlencode($_SESSION['username']); ?>">Email Client</a>
        </form>
        <?php if ($error): ?>
            <div class="alert alert-danger" style="margin-top:15px;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>


