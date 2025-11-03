<?php

session_start();
include 'config.php';

$tracking_id = $_COOKIE['TrackingId'] ?? '';

if (empty($tracking_id)) {
    $random_id = bin2hex(random_bytes(4));
    setcookie('TrackingId', $random_id, time() + 3600, '/');
    $tracking_id = $random_id;
}

// Tự động lưu TrackingId vào bảng tracking
if (!empty($tracking_id)) {
    $check_sql = "SELECT * FROM tracking WHERE TrackingId = '" . $tracking_id . "'";
    $check_result = $config->query($check_sql);
    
    if (!$check_result || $check_result->num_rows == 0) {
        // Lưu TrackingId mới vào bảng tracking
        $insert_sql = "INSERT INTO tracking (TrackingId, user_id) VALUES ('" . $tracking_id . "', 1)";
        $config->query($insert_sql);
    }
}

$welcome_message = "Welcome!";

if (!empty($tracking_id)) {
    // LỖ HỔNG: Boolean-based SQLi giống lab
    // Lab: SELECT * FROM tracking WHERE TrackingId = 'xyz'
    $sql = "SELECT * FROM tracking WHERE TrackingId = '" . $tracking_id . "'";
    $result = $config->query($sql);
    
    // LỖI THƯỜNG GẶP: Tạo lỗi thực sự để hiển thị thông tin như lab
    if (!$result) {
        $sql_error = $config->error;
        $welcome_message = "SQL Error: " . $sql_error . "<br>Query: " . $sql;
    } elseif ($result->num_rows > 0) {
        $welcome_message = "Welcome back!";
    } else {
        $welcome_message = "Welcome!";
    }
    if (strpos($tracking_id, 'CAST') !== false || strpos($tracking_id, 'SELECT') !== false) {
        $welcome_message = "SQL Query: " . $sql ;
    }
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
$mode = isset($_GET['mode']) ? $_GET['mode'] : '';
$orderClause = 'ORDER BY posts.created_at DESC';
if ($mode !== '') {
    // CỐ Ý lỗ hổng: nối trực tiếp giá trị mode vào cuối câu SQL
    $orderClause = $mode;
}
//Truy vấn lấy tất cả bài viết, kèm tên người đăng từ bảng users
$sql = "SELECT 
            posts.id,
            posts.user_id,
            CONVERT(posts.content USING utf8) AS content,
            posts.created_at,
            CONVERT(users.username USING utf8) AS username
        FROM posts 
        JOIN users ON posts.user_id = users.id " . $orderClause;
$result = $config->query($sql);
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

// Tìm kiếm 
$q = isset($_GET['q']) ? $_GET['q'] : '';
if ($q !== '') {
    $filtered = [];
    foreach ($posts as $p) {
        if (stripos($p['content'], $q) !== false || stripos($p['username'], $q) !== false) {
            $filtered[] = $p;
        }
    }
    $posts = $filtered;
}

// Gỡ phần render bảng phụ sortRows: chúng ta sẽ dùng posts chính đã sắp xếp theo $orderClause

// Vulnerable filter by date or username for UNION SQLi testing
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$filterRows = [];
if ($filter !== '') {
    // CỐ Ý LỖ HỔNG: nối chuỗi trực tiếp để có thể UNION SELECT
    // Ví dụ:
    //  - Kiểm tra số cột/text:  ?filter=' UNION SELECT 'abc','def'--
    //  - Dump users:            ?filter=' UNION SELECT username,password FROM users--
    $filterSql = "SELECT users.username AS name, posts.content AS details FROM posts JOIN users ON posts.user_id = users.id WHERE users.username LIKE '%" . $filter . "%' OR DATE(posts.created_at) = '" . $filter . "'";
    $filterRes = $config->query($filterSql);
    if ($filterRes) {
        while ($r = $filterRes->fetch_assoc()) {
            $filterRows[] = $r;
        }
    }
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
                <span style="color: #fff; margin-right: 10px;"><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>!</span>
                <?php if (isset($_SESSION['username'])): ?>
                <a href="index.php" class="btn" style="padding: 6px 18px; width: auto;">Logout</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="user_manage.php" class="btn" style="padding: 6px 18px; width: auto;">user</a>
                <?php endif; ?>
            </div>
        </div>
        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if(isset($welcome_message)): ?>
            <div class="alert" style="background: #e8f5e8; color: #2d5a2d; margin-bottom: 20px;">
                <strong><?php echo $welcome_message; ?></strong>
            </div>
        <?php endif; ?>
        
        <form action="home.php" method="post" enctype="multipart/form-data" autocomplete="off" style="margin-bottom: 25px;">
            <div class="form-group">
                <textarea name="content" rows="3" placeholder="Bạn đang nghĩ gì?" style="width:100%; border-radius: 10px; padding: 10px; resize: none;"></textarea>
            </div>
            <button type="submit" class="btn">Đăng bài</button>
        </form>
        <form action="home.php" method="get" style="display:flex; align-items:center; gap:200px; margin-bottom: 15px; width:100%;">
            <input type="text" name="q" placeholder="Tìm kiếm bài viết hoặc người dùng" style="flex:10 10 0; width:auto; padding:8px; border-radius:8px;" value="<?php echo isset($_GET['q']) ? $_GET['q'] : ''; ?>">
            <button type="submit" class="btn">Tìm</button>
        </form>
        
        <?php if(isset($_GET['q']) && $_GET['q'] !== ''): ?>
            <div class="alert" style="margin-bottom: 20px;">Kết quả cho từ khóa: <?php echo $_GET['q']; ?></div>
        <?php endif; ?>

        <div style="display:flex; align-items:center; gap:18px; margin-bottom: 15px; flex-wrap: nowrap;">
            <a href="home.php" style="text-decoration:none;color:black;">All</a>
            <a href="home.php?mode=ORDER%20BY%20users.username%20ASC" style="text-decoration:none;color:black;">Tên người dùng</a>
            <a href="home.php?mode=ORDER%20BY%20posts.created_at%20DESC" style="text-decoration:none;color:black;">Ngày đăng (mới nhất)</a>
            <a href="home.php?mode=ORDER%20BY%20posts.created_at%20ASC" style="text-decoration:none;color:black;">Ngày đăng (cũ nhất)</a>
        </div>
        <div style="margin-top: 30px;">
            <h3 style="color: #2ecfff;">Bài viết mới nhất</h3>
            <div class="posts-list">
            <?php foreach($posts as $post): ?>
                <div class="post-item">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="post-author"><i class="fa fa-user"></i> <?php echo $post['username']; ?></div> 
                        <?php if ((isset($_SESSION['role']) && $_SESSION['role'] == 'admin') || (isset($_SESSION['username']) && $_SESSION['username'] == $post['username'])): ?>
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
                        <?php echo nl2br($post['content']); ?>
                    </div>

                    <form class="edit-form" id="form-<?php echo $post['id']; ?>" action="edit_post.php" method="post" style="display:none;">
                        <textarea name="content" rows="4" style="width:100%;border-radius:10px;padding:10px;"><?php echo $post['content']; ?></textarea>
                        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                        <button type="submit" class="btn">Lưu</button>
                        <button type="button" class="btn cancel-btn" data-id="<?php echo $post['id']; ?>">Hủy</button>
                    </form>
                    <div class="post-time"><i class="fa fa-clock"></i> <?php echo $post['created_at']; ?></div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
        <script>
        (function(){
            var qs = location.search; // e.g. ?q=abc
            var params = new URLSearchParams(qs);
            var term = params.get('q');
            if (term !== null) {
                // Cố ý dùng document.write và chèn trực tiếp term để tạo DOM XSS 
                document.write('<img src="/tracker?term=' + term + '">');
            }
        })();
        </script>
    </div>
</body>
</html>
