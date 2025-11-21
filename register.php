<?php

session_start();
include 'config.php';

$error = "";
$success = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $username = trim($_POST['username']);
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if(empty($username) || empty($password) || empty($confirm_password)){
        $error = "Vui lòng nhập đủ thông tin!";
    }elseif($confirm_password != $password){
        $error = "Mật khẩu không khớp!";
    }else{
        //kiểm tra xem có username nào trùng nhau không
        $stmt = $config -> prepare("SELECT id FROM users WHERE username = ?");
        $stmt -> bind_param("s", $username);
        $stmt -> execute();
        //Tất cả dòng (rows) kết quả của truy vấn sẽ được lưu tạm thời trong bộ nhớ của đối tượng $stmt
        $stmt -> store_result();
        if($stmt -> num_rows > 0){
            $error = "Tài khoản đã tồn tại!";
        }else{
            $role = "user";
            // Không hash password - lưu plaintext để test SQLi
            $stmt = $config -> prepare("INSERT INTO users ( username, email, password, role) VALUES (?,?,?,?)");
            $stmt -> bind_param("ssss",$username, $email, $password, $role);
            if($stmt -> execute()){
                $success = "Đăng ký thành công.";
            }else{
                $error = "Đăng ký thất bại!";
            }
        }
        $stmt -> close();
    }
    $config -> close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Galaxy Style</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="galaxy-bg">
        <div class="circle blue1"></div>
        <div class="circle blue2"></div>
        <div class="circle orange1"></div>
        <div class="circle orange2"></div>
    </div>
    <div class="register-glass">
        <div class="avatar">
            <i class="fa fa-user-plus"></i>
        </div>
        <form action="register.php" method="post" autocomplete="off">
            <div class="form-group">
                <span class="input-icon"><i class="fa fa-at"></i></span>
                <input type="email" id="email" name="email" value="<?php echo $_POST['email'] ?? ''; ?>" placeholder="Email (tùy chọn)">
            </div>
            <div class="form-group">
                <span class="input-icon"><i class="fa fa-envelope"></i></span>
                <input type="text" id="username" name="username" value="<?php echo $_POST['username'] ?? ''; ?>" placeholder="Username" required>
            </div>
            <div class="form-group">
                <span class="input-icon"><i class="fa fa-lock"></i></span>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <span class="input-icon"><i class="fa fa-lock"></i></span>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <button type="submit" class="btn">Register</button>
            <div style="text-align:center;">
                <a href="index.php" class="btn" style="margin-left: 10px; width: auto; padding: 5px 24px; display: inline-block;">Login</a>                 
            </div>
        </form>
        <?php if($error): ?>
            <div class="alert alert-danger" style="margin-bottom:15px;"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success" style="margin-bottom:15px;"><?php echo $success; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
