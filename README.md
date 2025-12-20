# Mini Social - Social Media Platform

## 📋 Giới thiệu dự án

**Mini Social** là một nền tảng mạng xã hội đơn giản được phát triển bằng PHP thuần, cho phép người dùng đăng ký, đăng bài viết, tìm kiếm và quản lý nội dung. Dự án này được thiết kế cho mục đích học tập và nghiên cứu về các lỗ hổng bảo mật web phổ biến.

[Hoàng Quang Sơn: 22810310275 ] |  Lý thuyết và Khai thác SQL Injection (SQLi, phân tích cơ chế SQLi và phương pháp phòng chống. Thiết lập Burp Suite Proxy, thực hiện demo khai thác SQLi (Bypass Login) bằng Burp Repeater và viết phần khắc phục code SQLi.        


[Phùng Tiến Đạt: 22810310228 ] |  Lý thuyết và Khai thác Cross-Site Scripting (XSS), phân tích cơ chế XSS và phương pháp phòng chống. Code ứng dụng web demo, thực hiện demo khai thác Stored XSS (đánh cắp Cookie) và viết phần khắc phục code XSS.         


[Phan Đức Dương: 22810310282 ] |  Cơ sở hạ tầng và Tổng hợp Báo cáo: Cài đặt và cấu hình môi trường thực nghiệm (XAMPP, CSDL). Hỗ trợ chụp ảnh minh họa Burp Suite, biên tập và tổng hợp báo cáo chuyên đề    


<img width="748" height="581" alt="image" src="https://github.com/user-attachments/assets/58511424-b0bf-46e9-8b9c-235d886c304b" />              
<img width="1130" height="95" alt="image" src="https://github.com/user-attachments/assets/a04fbf32-6248-4b73-80cd-9fd3146ce3d5" />               

<img width="722" height="253" alt="image" src="https://github.com/user-attachments/assets/aed424c3-aead-4b35-a602-47369884e9ee" />         
<img width="1363" height="619" alt="image" src="https://github.com/user-attachments/assets/f3df4d9d-3233-49c7-93c8-992a5814e835" />             

<img width="1814" height="352" alt="image" src="https://github.com/user-attachments/assets/feb23341-7b5d-46fd-8909-ca8fdb9f787b" />                      
<img width="819" height="424" alt="image" src="https://github.com/user-attachments/assets/846b2e02-3c8d-4faa-a1cc-d2a534652121" />                          

<img width="702" height="251" alt="image" src="https://github.com/user-attachments/assets/d838d070-8aef-4247-b0b8-b8f5c7a56ab9" />                 








## 🚀 Tính năng chính

### 👤 Quản lý người dùng
- ✅ Đăng ký tài khoản mới (với email optional)
- ✅ Đăng nhập với 2FA (OTP qua mock email)
- ✅ Xác thực 2FA với OTP code
- ✅ Quên mật khẩu và đặt lại (insecure flow)
- ✅ Đăng xuất
- ✅ Đổi username (POST và GET methods)
- ✅ Quản lý profile với avatar
- ✅ Phân quyền (User/Admin)
- ⚠️ Username enumeration (timing attack)
- ⚠️ Rate limiting bypass (X-Forwarded-For)

### 📝 Quản lý bài viết
- ✅ Đăng bài viết mới
- ✅ Xem danh sách bài viết với sắp xếp động
- ✅ Chỉnh sửa bài viết (missing authorization check)
- ✅ Xóa bài viết (CSRF vulnerable)
- ✅ Sắp xếp bài viết (theo tên, ngày - SQLi vulnerable)

### 🔍 Tìm kiếm
- ✅ Tìm kiếm bài viết theo nội dung
- ✅ Tìm kiếm theo tên người dùng
- ✅ Filter và sort kết quả
- ⚠️ XSS vulnerable trong search query

### 👨‍💼 Admin Dashboard
- ✅ Quản lý người dùng (role bypass với ?role=admin)
- ✅ Thêm/Xóa/Sửa người dùng
- ✅ Phân quyền cho người dùng
- ✅ Xem danh sách tất cả users

### 🔐 Security Testing Features
- ✅ Cookie tracking (SQLi testable via TrackingId)
- ✅ Mock email client để xem OTP
- ✅ Strict SQL mode để test type mismatch
- ✅ Tự động phát hiện sai kiểu dữ liệu cột trong UNION SELECT
- ✅ Error feedback cho SQLi testing

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
- ⚠️ SQL Injection vulnerabilities (Boolean-based, UNION-based, Authentication bypass)
- ⚠️ XSS (Cross-Site Scripting) - DOM-based và Reflected
- ⚠️ CSRF (Cross-Site Request Forgery) - Token bypass và GET-based
- ⚠️ Path Traversal - Truy cập files ngoài thư mục uploads
- ⚠️ Authentication bypass - Role injection, 2FA issues
- ⚠️ Authorization bypass - Missing ownership checks
- ⚠️ Username enumeration - Timing attacks
- ⚠️ Rate limiting bypass - X-Forwarded-For spoofing
- ⚠️ Session management issues - Missing security flags
- ⚠️ Plaintext password storage - Không hash passwords
- ⚠️ Insecure password reset - Token không được validate
- ⚠️ Input validation issues - Không trim/validate để test vulnerabilities

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
CREATE DATABASE mini_social_error CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Sử dụng database
USE mini_social_error;

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

-- Tạo bảng emails (mock email cho 2FA)
CREATE TABLE emails (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    otp_code VARCHAR(10),
    reset_token VARCHAR(64),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert dữ liệu mẫu
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@example.com', 'admin123', 'admin'),
('user1', 'user1@example.com', 'password123', 'user'),
('test1', 'test1@example.com', 'test123', 'user');

INSERT INTO posts (user_id, content) VALUES 
(1, 'Welcome to Mini Social! This is the first post.'),
(2, 'Hello everyone! Nice to meet you all.'),
(1, 'Admin announcement: Please follow community guidelines.');
```

### Bước 3: Cấu hình Database
Mở file `config.php` và cấu hình thông tin database:
```php
<?php
$config = new mysqli('localhost', 'root', '', 'mini_social_error');
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
2. Nhập username, email (optional), password và xác nhận password
3. Click "Register"
4. Đăng nhập với tài khoản vừa tạo

### 2. Đăng nhập
1. Truy cập: `http://localhost/mini_social/index.php`
2. Nhập username và password
3. Click "Login"
4. Nhập OTP code (xem trong mock email tại `client_email.php`)
5. Sau khi verify 2FA, truy cập `my_account.php`

**Tài khoản mặc định:**
- **Admin**: username: `admin`, password: `admin123`
- **User**: username: `user1`, password: `password123`

### 3. Mock Email Client (xem OTP)
1. Truy cập: `http://localhost/mini_social/client_email.php`
2. Nhập username để xem email và OTP code
3. Copy OTP code để verify 2FA

### 4. Quên mật khẩu
1. Truy cập: `http://localhost/mini_social/forgot_password.php`
2. Nhập username
3. Xem link reset trong mock email (`client_email.php`)
4. Click link và nhập mật khẩu mới
5. **Lỗ hổng**: Có thể sửa hidden field `username` để đổi password người khác

### 5. Đăng bài viết
1. Sau khi đăng nhập và verify 2FA
2. Ở trang Home, nhập nội dung vào textarea "Bạn đang nghĩ gì?"
3. Click "Đăng bài"
4. Bài viết sẽ xuất hiện trong danh sách

### 6. Chỉnh sửa/Xóa bài viết
1. Hover vào bài viết của bạn
2. Click icon 3 chấm (⋮)
3. Chọn "Sửa" để chỉnh sửa hoặc "Xóa" để xóa bài
4. **Lỗ hổng**: Có thể edit bài của người khác nếu biết post_id

### 7. Tìm kiếm
1. Sử dụng search box ở đầu trang
2. Nhập từ khóa (tên người dùng hoặc nội dung)
3. Click "Tìm"
4. **Lỗ hổng**: Test XSS với payload `"><script>alert(1)</script>`

### 8. Sắp xếp bài viết
- **All**: Hiển thị tất cả bài viết
- **Tên người dùng**: Sắp xếp theo username A-Z
- **Ngày đăng (mới nhất)**: Bài mới nhất trước
- **Ngày đăng (cũ nhất)**: Bài cũ nhất trước
- **Lỗ hổng**: Tham số `mode` có SQLi - test với `?mode=UNION SELECT 1,2,3,4,5-- -`

### 9. Quản lý Profile
1. Click vào avatar ở góc phải
2. Truy cập Profile page
3. Đổi username bằng cách nhập username mới và click "Đổi username"
4. **Lỗ hổng**: CSRF bypass - bỏ qua parameter `csrf`

### 10. Admin - Quản lý người dùng
1. Đăng nhập với tài khoản admin
2. Click nút "user" ở góc phải
3. Xem danh sách người dùng
4. Thêm/Sửa/Xóa người dùng
5. **Lỗ hổng**: Bypass với `?role=admin` nếu không phải admin

## 🔒 Danh sách lỗ hổng bảo mật (Cho mục đích học tập)

### 1. SQL Injection (SQLi)
- **File**: `index.php` (line 38)
  - **Type**: Authentication bypass
  - **Exploit**: `admin' -- ` hoặc `' OR 1=1 -- ` để bypass login
  - **Note**: Username và password được nối trực tiếp vào câu SQL
  
- **File**: `home.php` (line 31, 87)
  - **Type**: Boolean-based SQLi, UNION-based SQLi
  - **Exploit via Cookie**: `TrackingId=xyz' OR 1=1 -- `
  - **Exploit via GET**: `?mode=UNION SELECT 1,2,3,4,5-- -`
  - **Note**: Tham số `mode` được nối trực tiếp vào ORDER BY clause
  - **Special Feature**: Có kiểm tra kiểu dữ liệu cột khi test UNION SELECT (báo cảnh báo nếu sai kiểu)

### 2. Authentication & Authorization Bypass
- **File**: `user_manage.php` (line 8)
  - **Type**: Authorization bypass
  - **Exploit**: `?role=admin` để bypass kiểm tra admin
  - **Note**: Ưu tiên lấy role từ GET parameter thay vì session

- **File**: `forgot_password.php` (line 6-18)
  - **Type**: Insecure password reset
  - **Exploit**: Server không kiểm tra token khi đặt lại mật khẩu, có thể sửa hidden username để đổi password user khác
  - **Note**: Token chỉ để trang trí, không được validate

### 3. CSRF (Cross-Site Request Forgery)
- **File**: `change_username.php` (line 20)
  - **Type**: CSRF token bypass
  - **Exploit**: Bỏ qua parameter `csrf` để bypass kiểm tra CSRF
  - **Note**: Chỉ kiểm tra CSRF khi có tham số csrf, nếu thiếu thì bỏ qua luôn

- **File**: `change_username2.php`
  - **Type**: GET-based CSRF
  - **Exploit**: Đổi username qua GET request

- **File**: `delete_post.php`
  - **Type**: GET-based CSRF
  - **Exploit**: Xóa bài viết qua GET request

### 4. XSS (Cross-Site Scripting)
- **File**: `home.php`
  - **Type**: DOM-based XSS, Reflected XSS
  - **Exploit**: `?q="><script>alert(1)</script>`
  
- **File**: `register.php`
  - **Type**: Reflected XSS
  - **Exploit**: Inject script vào username hoặc error messages

### 5. Weak Password Management
- **File**: `register.php` (line 33)
  - **Issue**: Passwords stored in plaintext (không hash)
  - **Note**: Mật khẩu lưu dạng văn bản thuần để dễ test SQLi

- **File**: `index.php` (line 39)
  - **Issue**: So sánh mật khẩu bằng plaintext

### 6. Username Enumeration
- **File**: `index.php` (line 42-49)
  - **Type**: Timing attack
  - **Exploit**: Delay khác nhau khi username tồn tại vs không tồn tại
  - **Note**: `usleep()` tỷ lệ với độ dài password khi username hợp lệ

### 7. Rate Limiting Bypass
- **File**: `index.php` (line 14-16)
  - **Type**: X-Forwarded-For bypass
  - **Exploit**: Thêm header `X-Forwarded-For` để bypass rate limit theo IP
  - **Note**: Tin tưởng header có thể bị forge

### 8. 2FA Bypass & Issues
- **File**: `index.php` (line 52-55)
  - **Issue**: Thiết lập session trước khi verify 2FA
  - **Note**: `$_SESSION['2fa_verified'] = false` nhưng đã set username và role

- **File**: `verify_2fa.php`
  - **Issue**: OTP được lưu trong database không mã hóa

### 9. Path Traversal
- **File**: `loadImage.php`
  - **Type**: Path traversal
  - **Exploit**: Truy cập files ngoài thư mục uploads
  - **Note**: Không validate đường dẫn file

### 10. Missing Authorization
- **File**: `edit_post.php`
  - **Issue**: Không kiểm tra ownership
  - **Note**: Users có thể edit bài viết của người khác

### 11. Session Security Issues
- Multiple files
  - **Issue**: Missing `session_regenerate_id()` sau login
  - **Issue**: Missing httponly, secure, samesite flags
  - **Note**: Dễ bị session fixation và session hijacking

### 12. Input Validation
- **File**: `index.php` (line 30-31)
  - **Issue**: Không trim input username trong login (giữ nguyên để test SQLi payload như `'-- `)
  
### 📊 Thống kê lỗ hổng
- **Critical**: SQL Injection (3), Authentication Bypass (2), CSRF (3)
- **High**: XSS (2), Plaintext Password (2), Path Traversal (1)
- **Medium**: Username Enumeration (1), Rate Limiting Bypass (1), 2FA Issues (2)
- **Low**: Session Security (2), Input Validation (1)

## 📁 Cấu trúc thư mục

```
mini_social/
├── index.php              # Trang đăng nhập (có SQLi, username enumeration, rate limit bypass)
├── register.php           # Trang đăng ký (plaintext password, XSS)
├── home.php              # Trang chủ feed bài viết (SQLi qua mode & TrackingId, XSS)
├── profile.php           # Trang profile người dùng
├── my_account.php        # Trang account sau khi verify 2FA
├── config.php            # Cấu hình database connection
├── verify_2fa.php        # Xác thực 2FA OTP
├── forgot_password.php   # Đặt lại mật khẩu (insecure reset flow)
├── client_email.php      # Mock email client để xem OTP
├── add_user.php          # Thêm người dùng (admin)
├── edit_user.php         # Sửa người dùng (admin)
├── delete_user.php       # Xóa người dùng (admin)
├── user_manage.php       # Quản lý người dùng (role bypass vulnerability)
├── edit_post.php         # Sửa bài viết (missing authorization)
├── delete_post.php       # Xóa bài viết (CSRF via GET)
├── change_username.php   # Đổi username POST (CSRF bypass)
├── change_username2.php  # Đổi username GET (CSRF vulnerable)
├── loadImage.php         # Load ảnh từ uploads (path traversal)
├── clean_xss.php         # Script xóa XSS payloads
├── styles.css            # CSS styling với Galaxy theme
├── style.js              # JavaScript functions
├── README.md             # File này
├── AUTH_VULNS_LIST.md    # Danh sách chi tiết lỗ hổng authentication
├── SQLI_TEST_GUIDE.md    # Hướng dẫn test SQL Injection
├── xss_payload.md        # Danh sách XSS payloads
├── csrf.html             # PoC CSRF attack
├── uploads/              # Thư mục chứa files upload (avatars, images)
└── views/                # Thư mục chứa HTML templates
    ├── index.html
    ├── home.html
    ├── register.html
    └── user_manage.html
```

## 🧪 Testing & Security

### Công cụ testing được sử dụng
- **Burp Suite** - Web vulnerability scanner và proxy
- **Custom Python scripts** - Automated testing và logging
- **Browser DevTools** - Inspect requests/responses, edit cookies

### File testing và documentation
- `test_sqli.py` - Script test SQL injection tự động
- `conditionall_error_lab.py` - Test conditional errors trong SQLi
- `time_base_lab.py` - Time-based blind SQLi testing
- `AUTH_VULNS_LIST.md` - Chi tiết lỗ hổng authentication
- `SQLI_TEST_GUIDE.md` - Hướng dẫn test SQLi (Boolean, UNION, type detection)
- `xss_payload.md` - Danh sách XSS payloads để test
- `csrf.html` - PoC CSRF attack

### Chạy tests
```bash
# Test SQL Injection
python test_sqli.py

# Test Time-based SQLi
python time_base_lab.py

# Test Conditional errors
python conditionall_error_lab.py

# Kết quả sẽ được lưu trong thư mục logs/
```

### SQLi Testing Quick Start
```bash
# Boolean-based SQLi qua Cookie
# Set TrackingId cookie: xyz' OR 1=1 --

# UNION-based SQLi qua GET parameter
http://localhost/mini_social/home.php?mode=UNION SELECT 1,2,3,4,5-- -

# Test type mismatch (cột int vs string)
http://localhost/mini_social/home.php?mode=UNION SELECT 'abc',2,3,4,5-- -
# Sẽ hiển thị cảnh báo "Có thể sai kiểu dữ liệu cột user_id"

# Authentication bypass
# Username: admin' --
# Password: anything
```

### XSS Testing Quick Start
```bash
# DOM-based XSS qua search
http://localhost/mini_social/home.php?q="><script>alert(document.cookie)</script>

# Reflected XSS trong error messages
# Test trong register.php, login form, etc.
```

### CSRF Testing Quick Start
```bash
# GET-based CSRF (delete post)
http://localhost/mini_social/delete_post.php?post_id=1

# CSRF token bypass (change username)
# Gửi POST request mà không có parameter csrf

# Role injection
http://localhost/mini_social/user_manage.php?role=admin
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
- [CSRF Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Cross-Site_Request_Forgery_Prevention_Cheat_Sheet.html)
- [PortSwigger Web Security Academy](https://portswigger.net/web-security)
- [OWASP Authentication Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html)

## 📝 Changelog

### Version 1.2.0 (Current - November 2025)
- ✅ Thêm 2FA với OTP (mock email system)
- ✅ Thêm tính năng quên mật khẩu (insecure reset flow)
- ✅ Thêm mock email client (`client_email.php`)
- ✅ Thêm kiểm tra kiểu dữ liệu cột cho UNION SELECT testing
- ✅ Bật strict SQL mode để test type mismatch
- ✅ Thêm rate limiting bypass qua X-Forwarded-For
- ✅ Thêm username enumeration qua timing attack
- ✅ Thêm role injection vulnerability trong user_manage.php
- ✅ Cải thiện error feedback cho SQLi testing
- ✅ Thêm documentation: SQLI_TEST_GUIDE.md, xss_payload.md

### Version 1.1.0
- ✅ Thêm tracking table cho Boolean-based SQLi
- ✅ Thêm UNION-based SQLi qua parameter mode
- ✅ Cải thiện CSRF vulnerabilities
- ✅ Thêm documentation về lỗ hổng

### Version 1.0.0
- ✅ Chức năng đăng ký/đăng nhập cơ bản
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
