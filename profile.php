<?php

session_start();

if (!isset($_SESSION['username'])) {
	header('Location: index.php');
	exit();
}

$avatar = isset($_GET['avatar']) ? $_GET['avatar'] : 'avatar.png';
// Show flash messages
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['success'], $_SESSION['error']);

if (empty($_SESSION['csrf'])) {
	$_SESSION['csrf'] = bin2hex(random_bytes(16));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User Profile</title>
	<link rel="stylesheet" href="styles.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
			<h2 style="margin: 0;">User Profile</h2>
			<div>
				<a href="home.php" class="btn" style="padding: 6px 18px; width: auto;">Home</a>
				<a href="login.php" class="btn" style="padding: 6px 18px; width: auto;">Logout</a>
			</div>
		</div>

		<?php if ($success): ?>
			<div class="alert alert-success" style="margin-bottom: 16px; color: #0f0;">
				<?php echo htmlspecialchars($success); ?>
			</div>
		<?php endif; ?>

		<?php if ($error): ?>
			<div class="alert alert-danger" style="margin-bottom: 16px; color: #ff2e63;">
				<?php echo htmlspecialchars($error); ?>
			</div>
		<?php endif; ?>
		<div style="display:flex; align-items:center; gap:20px;">
			<img src="loadImage.php?filename=<?php echo $avatar; ?>" alt="avatar" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:2px solid #2ecfff;">
			<div>
				<div style="color:#fff;font-size:20px;font-weight:600;">
					<i class="fa fa-user"></i> <?php echo $_SESSION['username']; ?>
				</div>
				<form action="change_username.php" method="POST" style="margin-top:12px; display:flex; gap:8px; align-items:center;">
					<input type="text" name="new_username" placeholder="Nhập username mới" required style="padding:10px; border:1px solid rgba(255,255,255,0.2); border-radius:5px; background: rgba(255,255,255,0.1); color:#fff;">
					<input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']); ?>">
					<button type="submit" class="btn" style="padding: 8px 18px;">Đổi username</button>
				</form>
			</div>
		</div>
	</div>
</body>
</html>


