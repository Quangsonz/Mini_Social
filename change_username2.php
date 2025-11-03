<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username'])) {
	header('Location: index.php');
	exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$newUsername = isset($_GET['new_username']) ? trim($_GET['new_username']) : '';
	if ($newUsername === '') {
		$_SESSION['error'] = 'Username mới không được để trống';
		header('Location: profile.php');
		exit();
	}
	$currentUsername = $_SESSION['username'];
	$stmt = $config->prepare("UPDATE users SET username = ? WHERE username = ?");
	if ($stmt) {
		$stmt->bind_param('ss', $newUsername, $currentUsername);
		if ($stmt->execute()) {
			$_SESSION['username'] = $newUsername;
			$_SESSION['success'] = 'Đổi username (GET) thành công!';
		} else {
			$_SESSION['error'] = 'Không thể cập nhật username (GET)';
		}
		$stmt->close();
	} else {
		$_SESSION['error'] = 'Lỗi hệ thống khi chuẩn bị truy vấn (GET)';
	}
	header('Location: profile.php');
	exit();
}

