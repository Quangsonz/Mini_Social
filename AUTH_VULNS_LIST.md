# DANH SÁCH LỖ HỔNG AUTHENTICATION


1. **SQL Injection** - `index.php:33`
   - `$sqlUser = "SELECT * FROM users WHERE username = '" . $username . "'";`
   
2. **Password Plain Text** - `index.php:39`, `register.php:30`
   - `if ($password === $row['password'])` - So sánh plain text
   - `$stmt->bind_param("sss", $username, $password, $role)` - Lưu plain text
   
3. **Username Enumeration** - `index.php:46-50`
   - Error message khác nhau: `"Invalid username or password."` vs `"Invalid username or password "`


4. **Không Validate Input** - `index.php:14-15`
   - Không trim, không validate format, không giới hạn độ dài
   
6. **Không có Rate Limiting** - `index.php`
   - Không giới hạn số lần login attempt
   
7. **Session Security** - `register.php`, `user_manage.php`
   - Thiếu security headers (httponly, secure, samesite)


8. **Inconsistent Error Handling** - `index.php:46-50`
   - Error messages không consistent
   
9. **Không có CSRF Protection** - `index.php`
   - Login form thiếu CSRF token
   
10. **Không Log Failed Attempts** - `index.php`
    - Không có logging cho brute force detection

---

### index.php
- **Dòng 14-15**: Input không được validate/trim
- **Dòng 33**: SQL Injection
- **Dòng 39**: Password comparison plain text
- **Dòng 46-50**: Username enumeration (error message khác nhau)
- **Thiếu**: Rate limiting, CSRF protection, logging

### register.php
- **Dòng 30-31**: Password lưu plain text (không hash)
- **Thiếu**: Session security headers

### home.php
- **Dòng 114**: SQL Injection trong filter parameter

### user_manage.php
- **Thiếu**: Session security headers (so với index.php)

