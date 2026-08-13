<?php
require_once __DIR__ . '/db.php';

$teachers = [];
if ($pdo) {
    try {
        $teachers = $pdo->query("SELECT * FROM teachers ORDER BY id DESC")->fetchAll();
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPT Architecture | จัดการคณาจารย์ (PHP Database Integrated)</title>
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
                <a href="teachers.php" class="active"><i class="fa-solid fa-user-group"></i> จัดการคณาจารย์</a>
                <a href="portfolios.php"><i class="fa-solid fa-folder-open"></i> จัดการผลงานนักศึกษา</a>
                <a href="news.php"><i class="fa-solid fa-newspaper"></i> จัดการข่าวประชาสัมพันธ์</a>
                <a href="users.php"><i class="fa-solid fa-users"></i> จัดการสมาชิก</a>
                <a href="contacts.php"><i class="fa-solid fa-inbox"></i> ข้อความสอบถาม</a>
                <a href="http://localhost:8082" target="_blank" class="highlight-link"><i class="fa-solid fa-database"></i> phpMyAdmin (8082)</a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <header class="topbar">
                <div class="topbar-title">
                    <h2>ระบบจัดการรายชื่อคณาจารย์ (PHP Direct Database)</h2>
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
                            <div class="metric-val" id="statTeachers"><?= count($teachers) ?></div>
                            <div class="metric-lbl">จำนวนอาจารย์ประจำแผนก</div>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon blue"><i class="fa-solid fa-graduation-cap"></i></div>
                        <div>
                            <div class="metric-val">100%</div>
                            <div class="metric-lbl">วุฒิตรงสายสถาปัตยกรรม</div>
                        </div>
                    </div>
                </div>

                <!-- Teacher Management Section -->
                <div class="section-card">
                    <div class="card-header flex-header">
                        <h3><i class="fa-solid fa-user-group"></i> รายชื่ออาจารย์ประจำแผนกวิชาสถาปัตยกรรม (PHP PDO Rendered)</h3>
                        <button class="btn btn-primary" onclick="openAdminModal('addTeacherModal')"><i class="fa-solid fa-plus"></i> เพิ่มอาจารย์ใหม่</button>
                    </div>

                    <div style="padding: 20px 30px 0 30px;">
                        <div class="toolbar-container">
                            <div class="search-box">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" id="searchTeacherInput" class="search-input" placeholder="ค้นหาชื่อ, ตำแหน่ง หรือความเชี่ยวชาญอาจารย์...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>รูปภาพ</th>
                                    <th>ชื่อ - นามสกุล</th>
                                    <th>ตำแหน่ง</th>
                                    <th>วุฒิการศึกษา</th>
                                    <th>อีเมล</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody id="adminTeachersTable">
                                <?php if (empty($teachers)): ?>
                                    <tr><td colspan="7" style="text-align:center; color: var(--text-muted); padding:30px;">ไม่พบข้อมูลอาจารย์ใน Database</td></tr>
                                <?php else: ?>
                                    <?php foreach ($teachers as $t): ?>
                                        <tr>
                                            <td><?= $t['id'] ?></td>
                                            <td><img src="<?= htmlspecialchars($t['image']) ?>" alt="<?= htmlspecialchars($t['name']) ?>" class="table-thumb" onerror="this.src='https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&auto=format&fit=crop&q=80'"></td>
                                            <td><strong><?= htmlspecialchars($t['name']) ?></strong></td>
                                            <td><?= htmlspecialchars($t['position']) ?></td>
                                            <td><?= htmlspecialchars($t['degree'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($t['email'] ?? '-') ?></td>
                                            <td>
                                                <div class="action-btns">
                                                    <button class="btn btn-warning btn-sm" onclick="editTeacherModal(<?= $t['id'] ?>)"><i class="fa-solid fa-pen-to-square"></i> แก้ไข</button>
                                                    <button class="btn btn-danger btn-sm" onclick="deleteItem('teachers_delete', <?= $t['id'] ?>)"><i class="fa-solid fa-trash"></i> ลบ</button>
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

    <!-- Add Teacher Modal -->
    <div id="addTeacherModal" class="modal-overlay hidden">
        <div class="modal-card">
            <button class="modal-close" onclick="closeAdminModal('addTeacherModal')">&times;</button>
            <h3><i class="fa-solid fa-user-plus"></i> เพิ่มรายชื่ออาจารย์ประจำแผนก</h3>
            <form id="addTeacherForm">
                <div class="form-group">
                    <label>ชื่อ - นามสกุล</label>
                    <input type="text" id="teacherName" required placeholder="เช่น สถาปนิกวิทูร สุวรรณเดช">
                </div>
                <div class="form-group">
                    <label>ตำแหน่ง</label>
                    <input type="text" id="teacherPos" required placeholder="เช่น หัวหน้าแผนกวิชาสถาปัตยกรรม">
                </div>
                <div class="form-group">
                    <label>วุฒิการศึกษา</label>
                    <input type="text" id="teacherDegree" placeholder="เช่น สถ.ม. จุฬาลงกรณ์มหาวิทยาลัย">
                </div>
                <div class="form-group">
                    <label>สาขาความเชี่ยวชาญ</label>
                    <input type="text" id="teacherExpertise" placeholder="เช่น Tropical Architecture, BIM">
                </div>
                <div class="form-group">
                    <label>อีเมล</label>
                    <input type="email" id="teacherEmail" placeholder="teacher@kpt.ac.th">
                </div>
                <div class="form-group">
                    <label>รูปภาพอาจารย์ (URL)</label>
                    <input type="url" id="teacherImage" placeholder="https://images.unsplash.com/photo-1560250097-0b93528c311a">
                </div>
                <div class="form-btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeAdminModal('addTeacherModal')">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึกข้อมูลอาจารย์</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Teacher Modal -->
    <div id="editTeacherModal" class="modal-overlay hidden">
        <div class="modal-card">
            <button class="modal-close" onclick="closeAdminModal('editTeacherModal')">&times;</button>
            <h3><i class="fa-solid fa-user-pen"></i> แก้ไขข้อมูลอาจารย์</h3>
            <form id="editTeacherForm">
                <input type="hidden" id="editTeacherId">
                <div class="form-group">
                    <label>ชื่อ - นามสกุล</label>
                    <input type="text" id="editTeacherName" required>
                </div>
                <div class="form-group">
                    <label>ตำแหน่ง</label>
                    <input type="text" id="editTeacherPos" required>
                </div>
                <div class="form-group">
                    <label>วุฒิการศึกษา</label>
                    <input type="text" id="editTeacherDegree">
                </div>
                <div class="form-group">
                    <label>สาขาความเชี่ยวชาญ</label>
                    <input type="text" id="editTeacherExpertise">
                </div>
                <div class="form-group">
                    <label>อีเมล</label>
                    <input type="email" id="editTeacherEmail">
                </div>
                <div class="form-group">
                    <label>รูปภาพอาจารย์ (URL)</label>
                    <input type="url" id="editTeacherImage" oninput="document.getElementById('editTeacherPreview').src = this.value">
                    <img id="editTeacherPreview" src="" alt="Preview" class="modal-thumb-preview">
                </div>
                <div class="form-btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeAdminModal('editTeacherModal')">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
