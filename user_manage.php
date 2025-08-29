<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['success'], $_SESSION['error']);

$users = [];
$sql = "SELECT id, username, role FROM users";
$result = $config->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="style.js" defer></script>
    <style>
        /* Global styles from home.php, simplified */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .galaxy-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #0f0f2a, #20204a);
            overflow: hidden;
            z-index: -1;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.6;
            filter: blur(80px);
        }

        .blue1 { top: 10%; left: 15%; width: 200px; height: 200px; background-color: #2ecfff; animation: move1 15s infinite alternate; }
        .blue2 { bottom: 20%; right: 10%; width: 250px; height: 250px; background-color: #2ecfff; animation: move2 20s infinite alternate; }
        .orange1 { top: 30%; right: 20%; width: 180px; height: 180px; background-color: #ff2e63; animation: move3 18s infinite alternate; }
        .orange2 { bottom: 5%; left: 25%; width: 220px; height: 220px; background-color: #ff2e63; animation: move4 22s infinite alternate; }

        @keyframes move1 { 0% { transform: translate(0, 0); } 100% { transform: translate(50px, 80px); } }
        @keyframes move2 { 0% { transform: translate(0, 0); } 100% { transform: translate(-70px, -40px); } }
        @keyframes move3 { 0% { transform: translate(0, 0); } 100% { transform: translate(-30px, 60px); } }
        @keyframes move4 { 0% { transform: translate(0, 0); } 100% { transform: translate(90px, -50px); } }

        .home-glass {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 30px;
            max-width: 900px;
            margin: 50px auto;
            color: white;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        /* User Management Specific Styles */
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header-content h2 {
            margin: 0;
            color: #fff;
        }

        .btn {
            padding: 8px 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            width: auto;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .btn-add {
            background: #28a745;
            color: white;
        }

        .search-box {
            margin-bottom: 20px;
        }

        .search-box input {
            width: 100%;
            padding: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 16px;
        }

        .search-box input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            color: white;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        th {
            background: rgba(255, 255, 255, 0.1);
            font-weight: bold;
        }

        tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.05);
        }

        .role-admin {
            color: #ff2e63;
            font-weight: bold;
        }

        .role-user {
            color: #2ecfff;
            font-weight: bold;
        }

        .btn-action {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            margin-right: 5px;
        }

        .btn-edit {
            background-color: rgba(0, 123, 255, 0.7);
            color: white;
        }

        .btn-delete {
            background-color: rgba(220, 53, 69, 0.7);
            color: white;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: #2c3e50;
            margin: 10% auto;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            color: white;
            position: relative;
        }

        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-button:hover,
        .close-button:focus {
            color: #fff;
            text-decoration: none;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: rgba(255, 255, 255, 0.8);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2ecfff;
        }

        .form-actions {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="galaxy-bg">
        <div class="circle blue1"></div>
        <div class="circle blue2"></div>
        <div class="circle orange1"></div>
        <div class="circle orange2"></div>
    </div>

    <div class="home-glass">
        <div class="header-content">
            <h2>Quản lý người dùng</h2>
            <div>
            <span style="color: #fff; margin-right: 10px;"><?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="index.php" class="btn" style="padding: 6px 18px; width: auto;">Logout</a>
                <a href="home.php" class="btn" style="padding: 6px 18px; width: auto;">Home</a>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Tìm kiếm người dùng...">
        </div>

        <button class="btn btn-add" style="margin-bottom: 20px;" onclick="openAddUserModal()">
            <i class="fas fa-plus"></i> Thêm người dùng mới
        </button>

        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên người dùng</th>
                    <th>Vai trò</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <?php foreach($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td>
                        <span class="<?php echo $user['role'] === 'admin' ? 'role-admin' : 'role-user'; ?>">
                            <?php echo $user['role'] === 'admin' ? 'Admin' : 'User'; ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-action btn-edit" style="width: 100px;" onclick="openEditModal(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>', '<?php echo htmlspecialchars($user['role']); ?>')">
                            <i class="fas fa-edit"></i> Sửa
                        </button>
                        <form action="delete_user.php" method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="btn btn-action btn-delete" style="width: 100px;" onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal('editUserModal')">&times;</span>
            <h3>Chỉnh sửa người dùng</h3>
            <form action="edit_user.php" method="POST">
                <div class="form-group">
                    <label for="editUserId">ID</label>
                    <input type="text" id="editUserId" name="id" readonly>
                </div>
                <div class="form-group">
                    <label for="editUsername">Username</label>
                    <input type="text" id="editUsername" name="username" required>
                </div>
                <div class="form-group">
                    <label for="editRole">Role</label>
                    <select id="editRole" name="role" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-add">Lưu thay đổi</button>
                    <button type="button" class="btn" onclick="closeModal('editUserModal')">Hủy</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal('addUserModal')">&times;</span>
            <h3>Thêm người dùng mới</h3>
            <form action="add_user.php" method="POST">
                <div class="form-group">
                    <label for="newUsername">Username</label>
                    <input type="text" id="newUsername" name="username" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">Password</label>
                    <input type="password" id="newPassword" name="password" required>
                </div>
                <div class="form-group">
                    <label for="newRole">Role</label>
                    <select id="newRole" name="role" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-add">Thêm người dùng</button>
                    <button type="button" class="btn" onclick="closeModal('addUserModal')">Hủy</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Chức năng tìm kiếm
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#userTableBody tr');
            
            rows.forEach(row => {
                const username = row.cells[1].textContent.toLowerCase();
                const role = row.cells[2].textContent.toLowerCase();
                
                if (username.includes(searchTerm) || role.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        function openAddUserModal() {
            document.getElementById('addUserModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function openEditModal(id, username, role) {
            document.getElementById('editUserId').value = id;
            document.getElementById('editUsername').value = username;
            document.getElementById('editRole').value = role;
            document.getElementById('editUserModal').style.display = 'block';
        }

        // Đóng modal khi click ra ngoài
        window.onclick = function(event) {
            const editModal = document.getElementById('editUserModal');
            const addModal = document.getElementById('addUserModal');
            if (event.target === editModal) {
                closeModal('editUserModal');
            } else if (event.target === addModal) {
                closeModal('addUserModal');
            }
        }
    </script>
</body>
</html>
