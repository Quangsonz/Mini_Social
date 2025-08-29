<?php
// VULN: chỉ để phục vụ test path traversal
$baseDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
$filename = $_GET['filename'] ?? '';
$path = $baseDir . $filename; // lỗi: nối thẳng chuỗi

if (!file_exists($path)) {
	header("HTTP/1.1 404 Not Found");
	exit('Not found');
}

// best-effort content type
$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
$mime = 'application/octet-stream';
if ($ext === 'png') { $mime = 'image/png'; }
elseif ($ext === 'jpg' || $ext === 'jpeg') { $mime = 'image/jpeg'; }
elseif ($ext === 'gif') { $mime = 'image/gif'; }
elseif ($ext === 'webp') { $mime = 'image/webp'; }
elseif ($ext === 'svg') { $mime = 'image/svg+xml'; }
else { $mime = 'text/plain; charset=utf-8'; }

header('Content-Type: ' . $mime);
readfile($path);