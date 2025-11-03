# Mini Social - Social Media Platform

## 📋 Giới thiệu dự án

**Mini Social** là một nền tảng mạng xã hội đơn giản được phát triển bằng PHP thuần, cho phép người dùng đăng ký, đăng bài viết, tìm kiếm và quản lý nội dung. Dự án này được thiết kế cho mục đích học tập và nghiên cứu về các lỗ hổng bảo mật web phổ biến.

### ⚠️ Lưu ý quan trọng
Dự án này **CỐ Ý** chứa nhiều lỗ hổng bảo mật để phục vụ mục đích giáo dục và thử nghiệm. **KHÔNG** nên sử dụng trong môi trường production.

### 🎯 Mục đích
- Học tập và thực hành về bảo mật web
- Hiểu rõ các lỗ hổng: SQL Injection, XSS, CSRF, Path Traversal
- Thực hành kỹ thuật penetration testing
- Phát triển kỹ năng secure coding

## 🚀 Tính năng chính

### 👤 Quản lý người dùng
- ✅ Đăng ký tài khoản mới
- ✅ Đăng nhập/Đăng xuất
- ✅ Đổi username
- ✅ Quản lý profile với avatar
- ✅ Phân quyền (User/Admin)

### 📝 Quản lý bài viết
- ✅ Đăng bài viết mới
- ✅ Xem danh sách bài viết
- ✅ Chỉnh sửa bài viết
- ✅ Xóa bài viết
- ✅ Sắp xếp bài viết (theo tên, ngày)

### 🔍 Tìm kiếm
- ✅ Tìm kiếm bài viết theo nội dung
- ✅ Tìm kiếm theo tên người dùng
- ✅ Filter và sort kết quả

### 👨‍💼 Admin Dashboard
- ✅ Quản lý người dùng
- ✅ Thêm/Xóa/Sửa người dùng
- ✅ Phân quyền cho người dùng

## 🛠️ Công nghệ sử dụng

### Backend
- **PHP 7.4+** - Ngôn ngữ lập trình chính
- **MySQL/MariaDB** - Hệ quản trị cơ sở dữ liệu
- **MySQLi** - Extension để kết nối database

### Frontend
- **HTML5** - Cấu trúc trang web
- **CSS3** - Styling với Galaxy theme
- **JavaScript (Vanilla)** - Tương tác người dùng
- **Font Awesome 6.4.0** - Icon library
- **jQuery 3.6.0** - DOM manipulation

### Server
- **Apache/Nginx** - Web server
- **XAMPP/WAMP** - Local development environment

### Security Features (Intentionally Vulnerable)
- ⚠️ SQL Injection vulnerabilities
- ⚠️ XSS (Cross-Site Scripting)
- ⚠️ CSRF (Cross-Site Request Forgery)
- ⚠️ Path Traversal
- ⚠️ Session management issues
- ⚠️ Plaintext password storage

## 📦 Cài đặt

### Yêu cầu hệ thống
- PHP >= 7.4
- MySQL/MariaDB >= 5.7
- Apache/Nginx web server
- XAMPP/WAMP (khuyến nghị cho Windows)

### Bước 1: Clone hoặc tải project
```bash
# Clone từ Git
git clone <repository-url>

# Hoặc tải file ZIP và giải nén vào thư mục htdocs (XAMPP)
# Đường dẫn: C:\xampp\htdocs\mini_social
```

### Bước 2: Import Database
```sql
-- Tạo database
CREATE DATABASE mini_social CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Sử dụng database
USE mini_social;

-- Tạo bảng users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng posts
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tạo bảng tracking (cho SQL injection testing)
CREATE TABLE tracking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    TrackingId VARCHAR(50) NOT NULL,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert dữ liệu mẫu
INSERT INTO users (username, password, role) VALUES 
('admin', 'admin123', 'admin'),
('user1', 'password123', 'user'),
('test1', 'test123', 'user');

INSERT INTO posts (user_id, content) VALUES 
(1, 'Welcome to Mini Social! This is the first post.'),
(2, 'Hello everyone! Nice to meet you all.'),
(1, 'Admin announcement: Please follow community guidelines.');
```

### Bước 3: Cấu hình Database
Mở file `config.php` và cấu hình thông tin database:
```php
<?php
$config = new mysqli('localhost', 'root', '', 'mini_social');
if($config->connect_error){
    die('Kết nối thất bại ' . $config->connect_error);
}
$config->set_charset('utf8mb4');
?>
```

### Bước 4: Tạo thư mục uploads
```bash
# Tạo thư mục để lưu avatar và files
mkdir uploads
chmod 755 uploads  # Linux/Mac
```

Hoặc trên Windows, tạo thư mục `uploads` trong thư mục `mini_social`.

### Bước 5: Khởi động Server
```bash
# Nếu dùng XAMPP
# 1. Mở XAMPP Control Panel
# 2. Start Apache
# 3. Start MySQL

# Truy cập: http://localhost/mini_social
```

## 📖 Hướng dẫn sử dụng

### 1. Đăng ký tài khoản
1. Truy cập: `http://localhost/mini_social/register.php`
2. Nhập username, password và xác nhận password
3. Click "Register"
4. Đăng nhập với tài khoản vừa tạo

### 2. Đăng nhập
1. Truy cập: `http://localhost/mini_social/index.php`
2. Nhập username và password
3. Click "Login"

**Tài khoản mặc định:**
- **Admin**: username: `admin`, password: `admin123`
- **User**: username: `user1`, password: `password123`

### 3. Đăng bài viết
1. Sau khi đăng nhập, ở trang Home
2. Nhập nội dung vào textarea "Bạn đang nghĩ gì?"
3. Click "Đăng bài"
4. Bài viết sẽ xuất hiện trong danh sách

### 4. Chỉnh sửa/Xóa bài viết
1. Hover vào bài viết của bạn
2. Click icon 3 chấm (⋮)
3. Chọn "Sửa" để chỉnh sửa hoặc "Xóa" để xóa bài

### 5. Tìm kiếm
1. Sử dụng search box ở đầu trang
2. Nhập từ khóa (tên người dùng hoặc nội dung)
3. Click "Tìm"

### 6. Sắp xếp bài viết
- **All**: Hiển thị tất cả bài viết
- **Tên người dùng**: Sắp xếp theo username A-Z
- **Ngày đăng (mới nhất)**: Bài mới nhất trước
- **Ngày đăng (cũ nhất)**: Bài cũ nhất trước

### 7. Quản lý Profile
1. Click vào avatar ở góc phải
2. Truy cập Profile page
3. Đổi username bằng cách nhập username mới và click "Đổi username"

### 8. Admin - Quản lý người dùng
1. Đăng nhập với tài khoản admin
2. Click nút "user" ở góc phải
3. Xem danh sách người dùng
4. Thêm/Sửa/Xóa người dùng

## 🔒 Danh sách lỗ hổng bảo mật (Cho mục đích học tập)

### 1. SQL Injection
- **File**: `index.php` (line 34)
- **Exploit**: `admin' -- ` để bypass login
- **File**: `home.php` (line 31, 81)
- **Exploit**: Boolean-based SQLi, UNION-based SQLi

### 2. XSS (Cross-Site Scripting)
- **File**: `home.php` (line 238)
- **Type**: DOM-based XSS
- **Exploit**: `?q="><script>alert(1)</script>`
- **File**: `register.php` (line 74)
- **Type**: Reflected XSS

### 3. CSRF (Cross-Site Request Forgery)
- **File**: `change_username.php` (line 20)
- **Exploit**: Bypass CSRF token validation
- **File**: `change_username2.php` (line 11)
- **Exploit**: GET-based CSRF
- **File**: `delete_post.php` (line 33)
- **Exploit**: Delete posts via GET request

### 4. Plaintext Password Storage
- **File**: `register.php` (line 30)
- **Issue**: Passwords stored without hashing

### 5. Path Traversal
- **File**: `loadImage.php`
- **Exploit**: Access files outside uploads directory

### 6. Missing Authorization
- **File**: `edit_post.php`
- **Issue**: Users can edit other users' posts

### 7. Session Fixation
- Multiple files missing `session_regenerate_id()`

## 📁 Cấu trúc thư mục

```
mini_social/
├── index.php              # Trang đăng nhập
├── register.php           # Trang đăng ký
├── home.php              # Trang chủ (feed bài viết)
├── profile.php           # Trang profile người dùng
├── config.php            # Cấu hình database
├── add_user.php          # Thêm người dùng (admin)
├── edit_user.php         # Sửa người dùng (admin)
├── delete_user.php       # Xóa người dùng (admin)
├── user_manage.php       # Quản lý người dùng (admin)
├── edit_post.php         # Sửa bài viết
├── delete_post.php       # Xóa bài viết
├── change_username.php   # Đổi username (POST)
├── change_username2.php  # Đổi username (GET - vulnerable)
├── loadImage.php         # Load ảnh từ uploads
├── clean_xss.php         # Script xóa XSS payloads
├── styles.css            # CSS styling
├── style.js              # JavaScript functions
├── README.md             # File này
├── AUTH_VULNS_LIST.md    # Danh sách lỗ hổng authentication
├── uploads/              # Thư mục chứa files upload
└── views/                # Thư mục chứa HTML templates
    ├── index.html
    ├── home.html
    ├── register.html
    └── user_manage.html
```

## 🧪 Testing & Security

### Công cụ testing được sử dụng
- **Burp Suite** - Web vulnerability scanner
- **sqlmap** - SQL injection testing
- **Custom Python scripts** - Automated testing

### File testing
- `test_sqli.py` - Script test SQL injection
- `conditionall_error_lab.py` - Test conditional errors
- `time_base_lab.py` - Time-based blind SQLi

### Chạy tests
```bash
# Test SQL Injection
python test_sqli.py

# Kết quả sẽ được lưu trong thư mục logs/
```

## 🔧 Troubleshooting

### Lỗi kết nối database
```
Error: Kết nối thất bại Connection refused
```
**Giải pháp**: Kiểm tra MySQL đã chạy chưa, kiểm tra thông tin trong `config.php`

### Lỗi 404 Not Found
**Giải pháp**: Kiểm tra đường dẫn file, đảm bảo Apache đã start

### Lỗi Permission Denied (uploads)
```bash
# Linux/Mac
chmod 755 uploads/
chmod 644 uploads/*

# Windows: Right-click folder > Properties > Security > Edit permissions
```

### Session không hoạt động
**Giải pháp**: Kiểm tra `session.save_path` trong `php.ini`, đảm bảo thư mục tồn tại và có quyền write

## 📚 Tài liệu tham khảo

- [PHP Manual](https://www.php.net/manual/en/)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [SQL Injection Cheat Sheet](https://portswigger.net/web-security/sql-injection/cheat-sheet)
- [XSS Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html)

## 📝 Changelog

### Version 1.0.0 (Current)
- ✅ Chức năng đăng ký/đăng nhập
- ✅ CRUD bài viết
- ✅ Tìm kiếm và sắp xếp
- ✅ Admin panel
- ✅ Profile management
- ⚠️ Intentional security vulnerabilities for learning

## 👥 Đóng góp

Dự án này phục vụ mục đích giáo dục. Nếu bạn muốn đóng góp:
1. Fork repository
2. Tạo branch mới (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

## ⚖️ License

Dự án này được phát hành dưới MIT License - xem file LICENSE để biết thêm chi tiết.

## ⚠️ Disclaimer

Dự án này được tạo ra **CHỈ** cho mục đích giáo dục và nghiên cứu bảo mật. Các lỗ hổng được cố ý tạo ra để học tập. **KHÔNG** sử dụng trong môi trường thực tế hoặc production. Tác giả không chịu trách nhiệm về việc sử dụng sai mục đích.

---

**Developed for Educational Purposes** 🎓
**Author**: Security Research Team
**Last Updated**: November 2025