<?php
session_start();
include 'config.php';


if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Hiển thị thông báo thành công hoặc lỗi
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['success'], $_SESSION['error']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content'])) {
    $content = trim($_POST['content']);
    if (empty($content)) {
        $error = "Nội dung không được để trống!";
    } else {
        //gọi hàm getUserId() để lấy user_id tương ứng.
        $user_id = getUserId($_SESSION['username'], $config);
        $stmt = $config->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $content);
        $stmt->execute();
        $stmt->close();
        header('Location: home.php');
        exit();
    }
}


$posts = [];
//Truy vấn lấy tất cả bài viết, kèm tên người đăng từ bảng users, sắp xếp theo thời gian mới nhất
$sql = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC";
$result = $config->query($sql);
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

function getUserId($username, $config) {
    $stmt = $config->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $id = null;
    $stmt->bind_result($id);
    $stmt->fetch();
    $stmt->close();
    return $id;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Mini Social</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="style.js" defer></script>
</head>
<body>
    <div class="galaxy-bg">
        <div class="circle blue1"></div>
        <div class="circle blue2"></div>
        <div class="circle orange1"></div>
        <div class="circle orange2"></div>
    </div>
    <div class="home-glass">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;">Mini Social</h2>
            <div>
                <a href="profile.php?avatar=avatar.png" title="Profile" style="margin-right: 10px; display:inline-block; vertical-align:middle;">
                    <img src="loadImage.php?filename=avatar.png" alt="avatar" style="width:34px;height:34px;border-radius:50%;object-fit:cover;border:1px solid #2ecfff;vertical-align:middle;">
                </a>
                <span style="color: #fff; margin-right: 10px;"><?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="index.php" class="btn" style="padding: 6px 18px; width: auto;">Logout</a>
                <a href="user_manage.php" class="btn" style="padding: 6px 18px; width: auto;">user</a>
            </div>
        </div>
        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="home.php" method="post" enctype="multipart/form-data" autocomplete="off" style="margin-bottom: 25px;">
            <div class="form-group">
                <textarea name="content" rows="3" placeholder="Bạn đang nghĩ gì?" style="width:100%; border-radius: 10px; padding: 10px; resize: none;"></textarea>
            </div>
            <div class="form-group">
                <input type="file" name="image">
            </div>
            <button type="submit" class="btn">Đăng bài</button>
        </form>
        <div style="margin-top: 30px;">
            <h3 style="color: #2ecfff;">Bài viết mới nhất</h3>
            <div class="posts-list">
            <?php foreach($posts as $post): ?>
                <div class="post-item">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="post-author"><i class="fa fa-user"></i> <?php echo htmlspecialchars($post['username']); ?></div>
                        <?php if ($_SESSION['role'] == 'admin' || $_SESSION['username'] == $post['username']): ?>
                        <div class="post-menu">
                            <i class="fa fa-ellipsis-v menu-icon"></i>
                            <div class="menu-dropdown">
                                <button class="menu-dropdown-btn edit-btn" data-id="<?php echo $post['id']; ?>">Sửa</button>
                                <button class="menu-dropdown-btn delete-btn" onclick="if(confirm('Bạn có chắc muốn xóa bài này không?')){window.location='delete_post.php?id=<?php echo $post['id']; ?>';}return false;">Xóa</button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="post-content" id="content-<?php echo $post['id']; ?>">
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                    </div>

                    <form class="edit-form" id="form-<?php echo $post['id']; ?>" action="edit_post.php" method="post" style="display:none;">
                        <textarea name="content" rows="4" style="width:100%;border-radius:10px;padding:10px;"><?php echo htmlspecialchars($post['content']); ?></textarea>
                        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                        <button type="submit" class="btn">Lưu</button>
                        <button type="button" class="btn cancel-btn" data-id="<?php echo $post['id']; ?>">Hủy</button>
                    </form>
                    <div class="post-time"><i class="fa fa-clock"></i> <?php echo $post['created_at']; ?></div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
