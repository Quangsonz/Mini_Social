<?php

$baseDir = __DIR__ . "/uploads/" .DIRECTORY_SEPARATOR;
$filename = $_GET['filename'] ?? '';
$path = $baseDir . $filename; // lỗi nối chuỗi trực tiếp và không chuẩn hóa dường dẫn

// thiếu kiểm tra path có nằm trong thư mục uploads không chỉ kiểm tra tồn tại file
if (!file_exists($path)) { 
	header("HTTP/1.1 404 Not Found");
	exit('Not found');
}

// thiếu giới hạn định dạng file được phép tải
$mime = 'application/octet-stream';

header('Content-Type: ' . $mime);
readfile($path);