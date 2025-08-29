<?php
session_start();

if (!isset($_SESSION['username'])) {
	header('Location: index.php');
	exit();
}

$avatar = isset($_GET['avatar']) ? $_GET['avatar'] : 'avatar.png';
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
		<div style="display:flex; align-items:center; gap:20px;">
			<img src="loadImage.php?filename=<?php echo htmlspecialchars($avatar); ?>" alt="avatar" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:2px solid #2ecfff;">
			<div>
				<div style="color:#fff;font-size:20px;font-weight:600;">
					<i class="fa fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
				</div>
				
			</div>
		</div>
	</div>
</body>
</html>


