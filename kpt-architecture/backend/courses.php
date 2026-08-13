<?php
require_once __DIR__ . '/db.php';

$message = '';
$msgType = 'success';

// Handle Direct PHP POST Actions (Add, Edit, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['php_action']) && $pdo) {
    $action = $_POST['php_action'];
    try {
        if ($action === 'add') {
            $stmt = $pdo->prepare("INSERT INTO courses (level, title, duration, description, credits) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['level'], $_POST['title'], $_POST['duration'], $_POST['description'], (int)$_POST['credits']]);
            $message = 'เพิ่มหลักสูตรเรียบร้อยแล้ว (PHP Direct PDO)';
        } elseif ($action === 'edit') {
            $stmt = $pdo->prepare("UPDATE courses SET level = ?, title = ?, duration = ?, description = ?, credits = ? WHERE id = ?");
            $stmt->execute([$_POST['level'], $_POST['title'], $_POST['duration'], $_POST['description'], (int)$_POST['credits'], (int)$_POST['id']]);
            $message = 'แก้ไขหลักสูตรเรียบร้อยแล้ว (PHP Direct PDO)';
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
            $stmt->execute([(int)$_POST['id']]);
            $message = 'ลบหลักสูตรเรียบร้อยแล้ว (PHP Direct PDO)';
        }
    } catch (Exception $e) {
        $message = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
        $msgType = 'error';
    }
}

// Direct Database Fetch via PDO
$courses = [];
if ($pdo) {
    try {
        $courses = $pdo->query("SELECT * FROM courses ORDER BY id DESC")->fetchAll();
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPT Architecture | จัดการหลักสูตร (PHP Database Integrated)</title>
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
                <a href="courses.php" class="active"><i class="fa-solid fa-graduation-cap"></i> จัดการหลักสูตร</a>
                <a href="teachers.php"><i class="fa-solid fa-user-group"></i> จัดการคณาจารย์</a>
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
                    <h2>ระบบจัดการหลักสูตรที่เปิดสอน (PHP Direct Database)</h2>
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
                <?php if (!empty($message)): ?>
                    <div style="background: <?= $msgType === 'success' ? 'rgba(46, 213, 115, 0.15)' : 'rgba(255, 71, 87, 0.15)' ?>; border: 1px solid <?= $msgType === 'success' ? '#2ed573' : '#ff4757' ?>; color: <?= $msgType === 'success' ? '#2ed573' : '#ff4757' ?>; padding: 15px 20px; border-radius: 8px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid <?= $msgType === 'success' ? 'fa-check-circle' : 'fa-circle-exclamation' ?>"></i>
                        <span><?= htmlspecialchars($message) ?></span>
                    </div>
                <?php endif; ?>

                <!-- Metrics Grid Header -->
                <div class="metrics-grid">
                    <div class="metric-card">
                        <div class="metric-icon blue"><i class="fa-solid fa-book-bookmark"></i></div>
                        <div>
                            <div class="metric-val" id="statCourses"><?= count($courses) ?></div>
                            <div class="metric-lbl">หลักสูตรเปิดสอนทั้งหมด</div>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon green"><i class="fa-solid fa-graduation-cap"></i></div>
                        <div>
                            <div class="metric-val">2 ระดับ</div>
                            <div class="metric-lbl">หลักสูตร ปวช. และ ปวส.</div>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon purple"><i class="fa-solid fa-award"></i></div>
                        <div>
                            <div class="metric-val">189+</div>
                            <div class="metric-lbl">หน่วยกิตรวมการศึกษา</div>
                        </div>
                    </div>
                </div>

                <!-- Toolbar & Course Management Section -->
                <div class="section-card">
                    <div class="card-header flex-header">
                        <h3><i class="fa-solid fa-graduation-cap"></i> รายการหลักสูตรทั้งหมด (PHP Server Side Rendered)</h3>
                        <button class="btn btn-primary" onclick="openAdminModal('addCourseModal')"><i class="fa-solid fa-plus"></i> เพิ่มหลักสูตรใหม่</button>
                    </div>

                    <div style="padding: 20px 30px 0 30px;">
                        <div class="toolbar-container">
                            <div class="search-box">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" id="searchCourseInput" class="search-input" placeholder="ค้นหาชื่อหลักสูตร หรือรายละเอียด...">
                            </div>
                            <select id="filterCourseLevel" class="filter-select">
                                <option value="all">ทุกระดับการศึกษา</option>
                                <option value="ปวช.">ระดับ ปวช.</option>
                                <option value="ปวส.">ระดับ ปวส.</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ระดับการศึกษา</th>
                                    <th>ชื่อหลักสูตร</th>
                                    <th>ระยะเวลา</th>
                                    <th>หน่วยกิต</th>
                                    <th>รายละเอียด</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody id="adminCoursesTable">
                                <?php if (empty($courses)): ?>
                                    <tr><td colspan="7" style="text-align:center; color: var(--text-muted); padding:30px;">ไม่พบข้อมูลหลักสูตรใน Database</td></tr>
                                <?php else: ?>
                                    <?php foreach ($courses as $c): ?>
                                        <tr>
                                            <td><?= $c['id'] ?></td>
                                            <td><span class="tag tag-green"><?= htmlspecialchars($c['level']) ?></span></td>
                                            <td><strong><?= htmlspecialchars($c['title']) ?></strong></td>
                                            <td><?= htmlspecialchars($c['duration']) ?></td>
                                            <td><?= (int)$c['credits'] ?> หน่วยกิต</td>
                                            <td><?= htmlspecialchars($c['description'] ?? '-') ?></td>
                                            <td>
                                                <div class="action-btns">
                                                    <button class="btn btn-warning btn-sm" onclick="editCourseModal(<?= $c['id'] ?>)"><i class="fa-solid fa-pen-to-square"></i> แก้ไข</button>
                                                    <button class="btn btn-danger btn-sm" onclick="deleteItem('courses_delete', <?= $c['id'] ?>)"><i class="fa-solid fa-trash"></i> ลบ</button>
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

    <!-- Add Course Modal -->
    <div id="addCourseModal" class="modal-overlay hidden">
        <div class="modal-card">
            <button class="modal-close" onclick="closeAdminModal('addCourseModal')">&times;</button>
            <h3><i class="fa-solid fa-plus-circle"></i> เพิ่มหลักสูตรใหม่</h3>
            <form id="addCourseForm">
                <div class="form-group">
                    <label>ระดับการศึกษา</label>
                    <select id="courseLevel" class="form-control">
                        <option value="ปวช.">ปวช.</option>
                        <option value="ปวส.">ปวส.</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>ชื่อหลักสูตร</label>
                    <input type="text" id="courseTitle" required placeholder="เช่น ประกาศนียบัตรวิชาชีพ สาขาวิชาสถาปัตยกรรม">
                </div>
                <div class="form-group">
                    <label>ระยะเวลาเรียน</label>
                    <input type="text" id="courseDuration" required placeholder="เช่น 3 ปี (หลักสูตรตามโครงสร้างสอศ.)">
                </div>
                <div class="form-group">
                    <label>จำนวนหน่วยกิต</label>
                    <input type="number" id="courseCredits" value="100">
                </div>
                <div class="form-group">
                    <label>รายละเอียดหลักสูตร</label>
                    <textarea id="courseDesc" rows="3" placeholder="รายละเอียดวิชาเรียนและการเน้นทักษะ..."></textarea>
                </div>
                <div class="form-btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeAdminModal('addCourseModal')">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึกหลักสูตร</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Course Modal -->
    <div id="editCourseModal" class="modal-overlay hidden">
        <div class="modal-card">
            <button class="modal-close" onclick="closeAdminModal('editCourseModal')">&times;</button>
            <h3><i class="fa-solid fa-pen-to-square"></i> แก้ไขข้อมูลหลักสูตร</h3>
            <form id="editCourseForm">
                <input type="hidden" id="editCourseId">
                <div class="form-group">
                    <label>ระดับการศึกษา</label>
                    <select id="editCourseLevel" class="form-control">
                        <option value="ปวช.">ปวช.</option>
                        <option value="ปวส.">ปวส.</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>ชื่อหลักสูตร</label>
                    <input type="text" id="editCourseTitle" required>
                </div>
                <div class="form-group">
                    <label>ระยะเวลาเรียน</label>
                    <input type="text" id="editCourseDuration" required>
                </div>
                <div class="form-group">
                    <label>จำนวนหน่วยกิต</label>
                    <input type="number" id="editCourseCredits">
                </div>
                <div class="form-group">
                    <label>รายละเอียดหลักสูตร</label>
                    <textarea id="editCourseDesc" rows="3"></textarea>
                </div>
                <div class="form-btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeAdminModal('editCourseModal')">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
