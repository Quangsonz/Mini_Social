<?php
session_start();
require_once 'config.php';

// LỖ HỔNG: Luồng đặt lại mật khẩu không kiểm tra token khi xác nhận mật khẩu mới.
// - Nhập username để "gửi email" (ghi vào bảng emails) kèm link chứa token trên URL
// - Mở link -> form đổi mật khẩu có input hidden "username"
// - POST gửi mật khẩu mới tới /forgot_password.php?temp-forgot-password-token=...
//           Server KHÔNG kiểm tra token, và TIN TƯỞNG hidden username -> có thể sửa sang user khác

$message = '';
$error = '';

function base_url() {
    // Sửa lại nếu virtual host khác
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    return $scheme . '://' . $host . ($path ? $path : '');
}

// Bước 1: Người dùng yêu cầu đặt lại qua username
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'request') {
    // CỐ Ý: không trim/validate username kỹ càng
    $username = isset($_POST['username']) ? $_POST['username'] : '';

    if ($username === '') {
        $error = 'Vui lòng nhập username';
    } else {
        // Tạo token ngẫu nhiên để đưa lên URL (nhưng sẽ KHÔNG dùng để xác thực ở bước POST)
        $token = bin2hex(random_bytes(16));

        // Ghi nhớ username trong session để tự động điền vào hidden field ở form đổi mật khẩu
        $_SESSION['last_reset_user'] = $username;

        // Lấy email của user (nếu có) để hiển thị trên client_email
        $email = '';
        $q = $config->query("SELECT email FROM users WHERE username='".$config->real_escape_string($username)."' LIMIT 1");
        if ($q && $q->num_rows > 0) {
            $row = $q->fetch_assoc();
            $email = (string)$row['email'];
            $q->free();
        }

        $resetLink = base_url() . '/forgot_password.php?temp-forgot-password-token=' . urlencode($token);

        // Gửi "email" bằng cách ghi vào bảng emails để người dùng xem trong client_email.php
        $ins = $config->prepare("INSERT INTO emails (username, email, subject, body, otp_code) VALUES (?,?,?,?,?)");
        if ($ins) {
            $subject = 'Password reset';
            $body = "Click link to reset: " . $resetLink;
            $otpCode = '';
            $ins->bind_param('sssss', $username, $email, $subject, $body, $otpCode);
            $ins->execute();
            $ins->close();
        }

        $message = 'Đã gửi email đặt lại mật khẩu (xem ở Email Client).';
    }
}

// Bước 3: Nhận mật khẩu mới qua POST (với token trên URL nhưng KHÔNG kiểm tra)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reset') {
    // LỖ HỔNG: Không kiểm tra temp-forgot-password-token ở URL
    // LỖ HỔNG: Tin tưởng hidden input username
    $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $targetUser  = isset($_POST['username']) ? $_POST['username'] : '';

    if ($newPassword === '' || $targetUser === '') {
        $error = 'Thiếu thông tin';
    } else {
        // BẢN VULN: Lưu mật khẩu dạng plaintext để dễ quan sát
        $stmt = $config->prepare('UPDATE users SET password = ? WHERE username = ?');
        if ($stmt) {
            $stmt->bind_param('ss', $newPassword, $targetUser);
            $stmt->execute();
            $stmt->close();
            $message = 'Đã cập nhật mật khẩu cho user: ' . htmlspecialchars($targetUser, ENT_QUOTES, 'UTF-8');
        } else {
            $error = 'Không thể cập nhật mật khẩu';
        }
    }
}

// Nếu có token trên URL -> hiển thị form đổi mật khẩu với hidden username
$hasToken = isset($_GET['temp-forgot-password-token']);
$prefillUser = isset($_SESSION['last_reset_user']) ? $_SESSION['last_reset_user'] : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-glass" style="max-width:520px;margin:40px auto;">
        <h2 style="text-align:center;color:white;">Quên mật khẩu</h2>

        <?php if ($message): ?>
            <div style="color:#4caf50;text-align:center;margin:10px 0;"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div style="color:#ff6b6b;text-align:center;margin:10px 0;"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!$hasToken): ?>
            <!-- Bước 1: Nhập username để gửi email reset -->
            <form method="post" action="forgot_password.php" autocomplete="off">
                <input type="hidden" name="action" value="request">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Nhập username" style="width:100%;" required>
                </div>
                <div style="display:flex;gap:10px;align-items:center;">
                    <button type="submit" class="btn">Gửi liên kết đặt lại</button>
                    <a href="client_email.php" class="btn" style="width:400px;">Email Client</a>
                </div>
                <div style="text-align:center;margin-top:10px;">
                    <a href="index.php" style="color:#ddd;">Quay lại đăng nhập</a>
                </div>
            </form>
        <?php else: ?>
            <!-- Bước 2: Đặt mật khẩu mới (LỖ HỔNG: không kiểm tra token, tin tưởng hidden username) -->
            <form method="post" action="forgot_password.php?temp-forgot-password-token=<?php echo urlencode($_GET['temp-forgot-password-token']); ?>" autocomplete="off">
                <input type="hidden" name="action" value="reset">
                <input type="hidden" name="username" value="<?php echo htmlspecialchars($prefillUser, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="form-group">
                    <input type="text" value="<?php echo htmlspecialchars($prefillUser, ENT_QUOTES, 'UTF-8'); ?>" disabled style="width:100%;opacity:.8;">
                </div>
                <div class="form-group">
                    <input type="password" name="new_password" placeholder="Mật khẩu mới" style="width:100%;" required>
                </div>
                <div style="display:flex;gap:10px;align-items:center;">
                    <button type="submit" class="btn">Đổi mật khẩu</button>
                    <a href="index.php" style="color:#ddd;">Quay lại đăng nhập</a>
                </div>
                <p style="color:#bbb;margin-top:10px;">
                     Form này cố tình chứa <code>username</code> dạng input ẩn. Server không kiểm tra token.
                </p>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>


