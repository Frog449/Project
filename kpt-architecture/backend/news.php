<?php
require_once __DIR__ . '/db.php';

$news = [];
if ($pdo) {
    try {
        $news = $pdo->query("SELECT * FROM news ORDER BY id DESC")->fetchAll();
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPT Architecture | จัดการข่าวประชาสัมพันธ์ (PHP Database Integrated)</title>
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
                <a href="news.php" class="active"><i class="fa-solid fa-newspaper"></i> จัดการข่าวประชาสัมพันธ์</a>
                <a href="users.php"><i class="fa-solid fa-users"></i> จัดการสมาชิก</a>
                <a href="contacts.php"><i class="fa-solid fa-inbox"></i> ข้อความสอบถาม</a>
                <a href="http://localhost:8082" target="_blank" class="highlight-link"><i class="fa-solid fa-database"></i> phpMyAdmin (8082)</a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <header class="topbar">
                <div class="topbar-title">
                    <h2>ระบบจัดการข่าวประชาสัมพันธ์และกิจกรรม (PHP Direct Database)</h2>
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
                        <div class="metric-icon blue"><i class="fa-solid fa-newspaper"></i></div>
                        <div>
                            <div class="metric-val" id="statNews"><?= count($news) ?></div>
                            <div class="metric-lbl">ข่าวสารและกิจกรรมทั้งหมด</div>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon green"><i class="fa-solid fa-bullhorn"></i></div>
                        <div>
                            <div class="metric-val">เผยแพร่สด</div>
                            <div class="metric-lbl">แสดงผลบนหน้าเว็บไซต์ทันที</div>
                        </div>
                    </div>
                </div>

                <!-- News Management Section -->
                <div class="section-card">
                    <div class="card-header flex-header">
                        <h3><i class="fa-solid fa-newspaper"></i> รายการข่าวประชาสัมพันธ์ทั้งหมด (PHP PDO Rendered)</h3>
                        <button class="btn btn-primary" onclick="openAdminModal('addNewsModal')"><i class="fa-solid fa-plus"></i> เพิ่มข่าวใหม่</button>
                    </div>

                    <div style="padding: 20px 30px 0 30px;">
                        <div class="toolbar-container">
                            <div class="search-box">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" id="searchNewsInput" class="search-input" placeholder="ค้นหาหัวข้อข่าว, หมวดหมู่ หรือเนื้อหา...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>รูปข่าว</th>
                                    <th>หัวข้อข่าวประชาสัมพันธ์</th>
                                    <th>หมวดหมู่</th>
                                    <th>ผู้เขียนข่าว</th>
                                    <th>วันที่โพสต์</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody id="adminNewsTable">
                                <?php if (empty($news)): ?>
                                    <tr><td colspan="7" style="text-align:center; color: var(--text-muted); padding:30px;">ไม่พบข้อมูลข่าวสารใน Database</td></tr>
                                <?php else: ?>
                                    <?php foreach ($news as $n): ?>
                                        <tr>
                                            <td><?= $n['id'] ?></td>
                                            <td><img src="<?= htmlspecialchars($n['image_url']) ?>" alt="<?= htmlspecialchars($n['title']) ?>" class="table-thumb" onerror="this.src='https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&auto=format&fit=crop&q=80'"></td>
                                            <td><strong><?= htmlspecialchars($n['title']) ?></strong></td>
                                            <td><span class="tag tag-green"><?= htmlspecialchars($n['category'] ?? 'ข่าวสาร') ?></span></td>
                                            <td><?= htmlspecialchars($n['author'] ?? 'ฝ่ายประชาสัมพันธ์') ?></td>
                                            <td><?= $n['post_date'] ?? '-' ?></td>
                                            <td>
                                                <div class="action-btns">
                                                    <button class="btn btn-warning btn-sm" onclick="editNewsModal(<?= $n['id'] ?>)"><i class="fa-solid fa-pen-to-square"></i> แก้ไข</button>
                                                    <button class="btn btn-danger btn-sm" onclick="deleteItem('news_delete', <?= $n['id'] ?>)"><i class="fa-solid fa-trash"></i> ลบ</button>
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

    <!-- Add News Modal -->
    <div id="addNewsModal" class="modal-overlay hidden">
        <div class="modal-card">
            <button class="modal-close" onclick="closeAdminModal('addNewsModal')">&times;</button>
            <h3><i class="fa-solid fa-newspaper"></i> เพิ่มข่าวประชาสัมพันธ์ใหม่</h3>
            <form id="addNewsForm">
                <div class="form-group">
                    <label>หัวข้อข่าว</label>
                    <input type="text" id="newsTitle" required placeholder="เช่น โครงการอบรมเขียนแบบ BIM 2026">
                </div>
                <div class="form-group">
                    <label>หมวดหมู่ข่าว</label>
                    <input type="text" id="newsCategory" value="ข่าวประชาสัมพันธ์" placeholder="เช่น ผลงานความภาคภูมิใจ, อบรมวิชาการ">
                </div>
                <div class="form-group">
                    <label>ผู้เขียนข่าว</label>
                    <input type="text" id="newsAuthor" value="ฝ่ายประชาสัมพันธ์">
                </div>
                <div class="form-group">
                    <label>วันที่ประกาศ</label>
                    <input type="date" id="newsDate" value="<?= date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label>รูปภาพข่าว (URL)</label>
                    <input type="url" id="newsImage" placeholder="https://images.unsplash.com/photo-1524178232363-1fb2b075b655">
                </div>
                <div class="form-group">
                    <label>รายละเอียดข่าวสาร</label>
                    <textarea id="newsContent" rows="4" placeholder="ระบุรายละเอียดกิจกรรมหรือข่าวสาร..."></textarea>
                </div>
                <div class="form-btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeAdminModal('addNewsModal')">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึกข่าวสาร</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit News Modal -->
    <div id="editNewsModal" class="modal-overlay hidden">
        <div class="modal-card">
            <button class="modal-close" onclick="closeAdminModal('editNewsModal')">&times;</button>
            <h3><i class="fa-solid fa-pen-to-square"></i> แก้ไขข่าวประชาสัมพันธ์</h3>
            <form id="editNewsForm">
                <input type="hidden" id="editNewsId">
                <div class="form-group">
                    <label>หัวข้อข่าว</label>
                    <input type="text" id="editNewsTitle" required>
                </div>
                <div class="form-group">
                    <label>หมวดหมู่ข่าว</label>
                    <input type="text" id="editNewsCategory">
                </div>
                <div class="form-group">
                    <label>ผู้เขียนข่าว</label>
                    <input type="text" id="editNewsAuthor">
                </div>
                <div class="form-group">
                    <label>วันที่ประกาศ</label>
                    <input type="date" id="editNewsDate">
                </div>
                <div class="form-group">
                    <label>รูปภาพข่าว (URL)</label>
                    <input type="url" id="editNewsImage" oninput="document.getElementById('editNewsPreview').src = this.value">
                    <img id="editNewsPreview" src="" alt="Preview" class="modal-thumb-preview">
                </div>
                <div class="form-group">
                    <label>รายละเอียดข่าวสาร</label>
                    <textarea id="editNewsContent" rows="4"></textarea>
                </div>
                <div class="form-btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeAdminModal('editNewsModal')">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
