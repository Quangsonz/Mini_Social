<?php
session_start();
require_once 'config.php';

$filterUser = isset($_GET['username']) ? trim($_GET['username']) : '';

$sql = "SELECT id, username, email, subject, body, otp_code, created_at FROM emails";
if ($filterUser !== '') {
    $sql .= " WHERE username = '" . $config->real_escape_string($filterUser) . "'";
}
$sql .= " ORDER BY created_at DESC LIMIT 100";
$result = $config->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Client (Test OTP)</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; color: white; }
        th { background:rgb(0, 0, 0); }
        code { background: #f7f7f7; padding: 2px 4px; }
    </style>
    </head>
<body>
    <h2 style="color: white;">Giả lập hộp thư — OTP xác thực</h2>
    <form method="get" style="margin-bottom:10px;">
        <input type="text" name="username" placeholder="Lọc theo username" value="<?php echo htmlspecialchars($filterUser, ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit">Lọc</button>
        <a href="client_email.php" style="margin-left:10px;">Bỏ lọc</a>
    </form>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>OTP</th>
                <th>Subject</th>
                <th>Body</th>
                <th>Thời gian</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo (int)$row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><code><?php echo htmlspecialchars($row['otp_code'], ENT_QUOTES, 'UTF-8'); ?></code></td>
                <td><?php echo htmlspecialchars($row['subject'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($row['body'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <?php endwhile; ?>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>


