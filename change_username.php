<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username'])) {
	header('Location: index.php');
	exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$newUsername = isset($_POST['new_username']) ? trim($_POST['new_username']) : '';
	$csrf = isset($_POST['csrf']) ? $_POST['csrf'] : '';

	if ($newUsername === '') {
		$_SESSION['error'] = 'Username mới không được để trống';
		header('Location: profile.php');
		exit();
	}
	// if (!isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $csrf)) {
	// Lỗ hổng: chỉ kiểm tra CSRF khi có tham số csrf, nếu thiếu thì bypass
	if ($csrf !== '' && (!isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $csrf))) {
		$_SESSION['error'] = 'CSRF token không hợp lệ';
		header('Location: profile.php');
		exit();
	}
	// Lấy thông tin user hiện tại
	$currentUsername = $_SESSION['username'];

	// Không có token CSRF 
	// Thực hiện cập nhật username trực tiếp dựa trên session hiện tại

	$stmt = $config->prepare("UPDATE users SET username = ? WHERE username = ?");
	if ($stmt) {
		$stmt->bind_param('ss', $newUsername, $currentUsername);
		if ($stmt->execute()) {
			$_SESSION['username'] = $newUsername;
			$_SESSION['success'] = 'Đổi username thành công!';
		} else {
			$_SESSION['error'] = 'Không thể cập nhật username';
		}
		$stmt->close();
	} else {
		$_SESSION['error'] = 'Lỗi hệ thống khi chuẩn bị truy vấn';
	}

	header('Location: profile.php');
	exit();
}

header('Location: profile.php');
exit();
?>


