<?php
require_once __DIR__ . '/db.php';

$users = [];
if ($pdo) {
    try {
        $users = $pdo->query("SELECT id, username, fullname, email, phone, role, created_at FROM users ORDER BY id DESC")->fetchAll();
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPT Architecture | จัดการสมาชิก (PHP Database Integrated)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="admin-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fa-solid fa-square-poll-vertical logo-icon"></i>
                <div>
                    <div class="brand-title">KPT ADMIN</div>
                    <div class="brand-sub">PHP Backend Portal (8081)</div>
                </div>
            </div>

            <nav class="sidebar-menu">
                <a href="http://localhost:8080" class="btn-back-link"><i class="fa-solid fa-arrow-left"></i> กลับสู่หน้าหลัก (8080)</a>
                <a href="index.php"><i class="fa-solid fa-chart-pie"></i> ภาพรวมระบบ</a>
                <a href="info.php"><i class="fa-solid fa-circle-info"></i> ข้อมูลแผนกวิชา</a>
                <a href="courses.php"><i class="fa-solid fa-graduation-cap"></i> จัดการหลักสูตร</a>
                <a href="teachers.php"><i class="fa-solid fa-user-group"></i> จัดการคณาจารย์</a>
                <a href="portfolios.php"><i class="fa-solid fa-folder-open"></i> จัดการผลงานนักศึกษา</a>
                <a href="news.php"><i class="fa-solid fa-newspaper"></i> จัดการข่าวประชาสัมพันธ์</a>
                <a href="users.php" class="active"><i class="fa-solid fa-users"></i> จัดการสมาชิก</a>
                <a href="contacts.php"><i class="fa-solid fa-inbox"></i> ข้อความสอบถาม</a>
                <a href="http://localhost:8082" target="_blank" class="highlight-link"><i class="fa-solid fa-database"></i> phpMyAdmin (8082)</a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <header class="topbar">
                <div class="topbar-title">
                    <h2>ระบบจัดการรายชื่อสมาชิก / ผู้ใช้งานระบบ (PHP Direct Database)</h2>
                    <span class="badge badge-success"><i class="fa-solid fa-circle"></i> Database: Connected (MySQL PDO)</span>
                </div>
                <div class="topbar-actions">
                    <a href="http://localhost:8080" class="btn-topbar-back"><i class="fa-solid fa-house"></i> กลับหน้าหลัก (8080)</a>
                    <div class="user-profile">
                        <i class="fa-solid fa-user-gear"></i> Administrator
                    </div>
                </div>
            </header>

            <div class="content-body">
                <!-- Metrics Grid Header -->
                <div class="metrics-grid">
                    <div class="metric-card">
                        <div class="metric-icon green"><i class="fa-solid fa-users"></i></div>
                        <div>
                            <div class="metric-val" id="statUsers"><?= count($users) ?></div>
                            <div class="metric-lbl">สมาชิกผู้ใช้งานในระบบ</div>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon blue"><i class="fa-solid fa-user-shield"></i></div>
                        <div>
                            <div class="metric-val">1 Admin</div>
                            <div class="metric-lbl">ผู้ดูแลระบบหลัก</div>
                        </div>
                    </div>
                </div>

                <!-- Users Management Section -->
                <div class="section-card">
                    <div class="card-header flex-header">
                        <h3><i class="fa-solid fa-users"></i> รายชื่อผู้ใช้งานที่ลงทะเบียนในระบบ (PHP PDO Rendered)</h3>
                        <button class="btn btn-primary" onclick="openAdminModal('addUserModal')"><i class="fa-solid fa-user-plus"></i> เพิ่มสมาชิกใหม่</button>
                    </div>

                    <div style="padding: 20px 30px 0 30px;">
                        <div class="toolbar-container">
                            <div class="search-box">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" id="searchUserInput" class="search-input" placeholder="ค้นหา Username, ชื่อ-นามสกุล หรืออีเมล...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ชื่อผู้ใช้ (Username)</th>
                                    <th>ชื่อ - นามสกุล</th>
                                    <th>อีเมล</th>
                                    <th>เบอร์โทรศัพท์</th>
                                    <th>สิทธิ์</th>
                                    <th>วันที่สมัคร</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody id="adminUsersTable">
                                <?php if (empty($users)): ?>
                                    <tr><td colspan="8" style="text-align:center; color: var(--text-muted); padding:30px;">ไม่พบข้อมูลสมาชิกใน Database</td></tr>
                                <?php else: ?>
                                    <?php foreach ($users as $u): ?>
                                        <tr>
                                            <td><?= $u['id'] ?></td>
                                            <td><strong>@<?= htmlspecialchars($u['username']) ?></strong></td>
                                            <td><?= htmlspecialchars($u['fullname']) ?></td>
                                            <td><?= htmlspecialchars($u['email']) ?></td>
                                            <td><?= htmlspecialchars($u['phone'] ?? '-') ?></td>
                                            <td><span class="tag <?= $u['role'] === 'admin' ? 'tag-green' : 'tag-gray' ?>"><?= htmlspecialchars($u['role']) ?></span></td>
                                            <td><?= $u['created_at'] ? substr($u['created_at'], 0, 10) : '-' ?></td>
                                            <td>
                                                <div class="action-btns">
                                                    <button class="btn btn-warning btn-sm" onclick="editUserModal(<?= $u['id'] ?>)"><i class="fa-solid fa-pen-to-square"></i> แก้ไข</button>
                                                    <?php if ($u['username'] !== 'admin'): ?>
                                                        <button class="btn btn-danger btn-sm" onclick="deleteItem('users_delete', <?= $u['id'] ?>)"><i class="fa-solid fa-trash"></i> ลบ</button>
                                                    <?php else: ?>
                                                        <span class="tag tag-gray">สงวนไว้</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="modal-overlay hidden">
        <div class="modal-card">
            <button class="modal-close" onclick="closeAdminModal('addUserModal')">&times;</button>
            <h3><i class="fa-solid fa-user-plus"></i> เพิ่มสมาชิกใหม่</h3>
            <form id="addUserForm">
                <div class="form-group">
                    <label>ชื่อผู้ใช้ (Username)</label>
                    <input type="text" id="userName" required placeholder="เช่น student2">
                </div>
                <div class="form-group">
                    <label>รหัสผ่าน (Password)</label>
                    <input type="password" id="userPassword" required placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label>ชื่อ - นามสกุล</label>
                    <input type="text" id="userFullname" required placeholder="นายสมชาย ใจดี">
                </div>
                <div class="form-group">
                    <label>อีเมล</label>
                    <input type="email" id="userEmail" required placeholder="somchai@kpt.ac.th">
                </div>
                <div class="form-group">
                    <label>เบอร์โทรศัพท์</label>
                    <input type="tel" id="userPhone" placeholder="081-234-5678">
                </div>
                <div class="form-group">
                    <label>สิทธิ์การใช้งาน (Role)</label>
                    <select id="userRole" class="form-control">
                        <option value="user">ผู้ใช้งานทั่วไป (User)</option>
                        <option value="admin">ผู้ดูแลระบบ (Admin)</option>
                    </select>
                </div>
                <div class="form-btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeAdminModal('addUserModal')">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึกสมาชิก</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="modal-overlay hidden">
        <div class="modal-card">
            <button class="modal-close" onclick="closeAdminModal('editUserModal')">&times;</button>
            <h3><i class="fa-solid fa-user-gear"></i> แก้ไขข้อมูลสมาชิก</h3>
            <form id="editUserForm">
                <input type="hidden" id="editUserId">
                <div class="form-group">
                    <label>ชื่อผู้ใช้ (Username - ไม่สามารถเปลี่ยนได้)</label>
                    <input type="text" id="editUserUsername" disabled style="opacity:0.7;">
                </div>
                <div class="form-group">
                    <label>ชื่อ - นามสกุล</label>
                    <input type="text" id="editUserFullname" required>
                </div>
                <div class="form-group">
                    <label>อีเมล</label>
                    <input type="email" id="editUserEmail" required>
                </div>
                <div class="form-group">
                    <label>เบอร์โทรศัพท์</label>
                    <input type="tel" id="editUserPhone">
                </div>
                <div class="form-group">
                    <label>สิทธิ์การใช้งาน (Role)</label>
                    <select id="editUserRole" class="form-control">
                        <option value="user">ผู้ใช้งานทั่วไป (User)</option>
                        <option value="admin">ผู้ดูแลระบบ (Admin)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>เปลี่ยนรหัสผ่านใหม่ (เว้นว่างไว้หากไม่ต้องการเปลี่ยน)</label>
                    <input type="password" id="editUserPassword" placeholder="••••••••">
                </div>
                <div class="form-btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeAdminModal('editUserModal')">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
