<?php
require_once __DIR__ . '/db.php';

$portfolios = [];
if ($pdo) {
    try {
        $portfolios = $pdo->query("SELECT * FROM portfolios ORDER BY id DESC")->fetchAll();
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPT Architecture | จัดการผลงานนักศึกษา (PHP Database Integrated)</title>
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
                <a href="portfolios.php" class="active"><i class="fa-solid fa-folder-open"></i> จัดการผลงานนักศึกษา</a>
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
                    <h2>ระบบจัดการคลังผลงานนักศึกษา (PHP Direct Database)</h2>
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
                        <div class="metric-icon purple"><i class="fa-solid fa-folder-open"></i></div>
                        <div>
                            <div class="metric-val" id="statPortfolios"><?= count($portfolios) ?></div>
                            <div class="metric-lbl">คลังผลงานรวมทั้งหมด</div>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon blue"><i class="fa-solid fa-cube"></i></div>
                        <div>
                            <div class="metric-val">3D & BIM</div>
                            <div class="metric-lbl">โปรแกรม Lumion / Revit / CAD</div>
                        </div>
                    </div>
                </div>

                <!-- Portfolio Management Section -->
                <div class="section-card">
                    <div class="card-header flex-header">
                        <h3><i class="fa-solid fa-folder-open"></i> รายการผลงานนักศึกษาแผนกวิชาสถาปัตยกรรม (PHP PDO Rendered)</h3>
                        <button class="btn btn-primary" onclick="openAdminModal('addPortfolioModal')"><i class="fa-solid fa-plus"></i> เพิ่มผลงานใหม่</button>
                    </div>

                    <div style="padding: 20px 30px 0 30px;">
                        <div class="toolbar-container">
                            <div class="search-box">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" id="searchPortInput" class="search-input" placeholder="ค้นหาชื่อผลงาน หรือนักศึกษาผู้สร้างสรรค์...">
                            </div>
                            <select id="filterPortLevel" class="filter-select">
                                <option value="all">ทุกระดับชั้น</option>
                                <option value="ปวช.3">ปวช.3</option>
                                <option value="ปวส.1">ปวส.1</option>
                                <option value="ปวส.2">ปวส.2</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>รูปผลงาน</th>
                                    <th>ชื่อโครงการ/ผลงาน</th>
                                    <th>ชื่อนักศึกษา</th>
                                    <th>ระดับชั้น</th>
                                    <th>หมวดหมู่</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody id="adminPortfoliosTable">
                                <?php if (empty($portfolios)): ?>
                                    <tr><td colspan="7" style="text-align:center; color: var(--text-muted); padding:30px;">ไม่พบข้อมูลผลงานนักศึกษาใน Database</td></tr>
                                <?php else: ?>
                                    <?php foreach ($portfolios as $p): ?>
                                        <tr>
                                            <td><?= $p['id'] ?></td>
                                            <td><img src="<?= htmlspecialchars($p['image_url']) ?>" alt="<?= htmlspecialchars($p['title']) ?>" class="table-thumb" onerror="this.src='https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&auto=format&fit=crop&q=80'"></td>
                                            <td><strong><?= htmlspecialchars($p['title']) ?></strong></td>
                                            <td><?= htmlspecialchars($p['student_name']) ?></td>
                                            <td><span class="tag tag-green"><?= htmlspecialchars($p['level']) ?></span></td>
                                            <td><?= htmlspecialchars($p['category'] ?? '-') ?></td>
                                            <td>
                                                <div class="action-btns">
                                                    <button class="btn btn-warning btn-sm" onclick="editPortfolioModal(<?= $p['id'] ?>)"><i class="fa-solid fa-pen-to-square"></i> แก้ไข</button>
                                                    <button class="btn btn-danger btn-sm" onclick="deleteItem('portfolios_delete', <?= $p['id'] ?>)"><i class="fa-solid fa-trash"></i> ลบ</button>
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

    <!-- Add Portfolio Modal -->
    <div id="addPortfolioModal" class="modal-overlay hidden">
        <div class="modal-card">
            <button class="modal-close" onclick="closeAdminModal('addPortfolioModal')">&times;</button>
            <h3><i class="fa-solid fa-folder-plus"></i> เพิ่มผลงานนักศึกษา</h3>
            <form id="addPortfolioForm">
                <div class="form-group">
                    <label>ชื่อโครงการ / ผลงาน</label>
                    <input type="text" id="portTitle" required placeholder="เช่น Eco-Urban Community Center">
                </div>
                <div class="form-group">
                    <label>ชื่อนักศึกษาผู้สร้างสรรค์</label>
                    <input type="text" id="portStudent" required placeholder="นายพีรพัฒน์ วงศ์สวัสดิ์">
                </div>
                <div class="form-group">
                    <label>ระดับชั้น</label>
                    <input type="text" id="portLevel" value="ปวส.2">
                </div>
                <div class="form-group">
                    <label>หมวดหมู่ผลงาน</label>
                    <input type="text" id="portCategory" placeholder="เช่น 3D Visualization & Design">
                </div>
                <div class="form-group">
                    <label>รูปภาพผลงาน (URL)</label>
                    <input type="url" id="portImage" placeholder="https://images.unsplash.com/photo-1600585154340-be6161a56a0c">
                </div>
                <div class="form-group">
                    <label>รายละเอียดผลงาน</label>
                    <textarea id="portDesc" rows="3" placeholder="แนวคิดการออกแบบและเทคโนโลยีที่ใช้..."></textarea>
                </div>
                <div class="form-btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeAdminModal('addPortfolioModal')">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึกผลงาน</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Portfolio Modal -->
    <div id="editPortfolioModal" class="modal-overlay hidden">
        <div class="modal-card">
            <button class="modal-close" onclick="closeAdminModal('editPortfolioModal')">&times;</button>
            <h3><i class="fa-solid fa-pen-to-square"></i> แก้ไขผลงานนักศึกษา</h3>
            <form id="editPortfolioForm">
                <input type="hidden" id="editPortId">
                <div class="form-group">
                    <label>ชื่อโครงการ / ผลงาน</label>
                    <input type="text" id="editPortTitle" required>
                </div>
                <div class="form-group">
                    <label>ชื่อนักศึกษาผู้สร้างสรรค์</label>
                    <input type="text" id="editPortStudent" required>
                </div>
                <div class="form-group">
                    <label>ระดับชั้น</label>
                    <input type="text" id="editPortLevel">
                </div>
                <div class="form-group">
                    <label>หมวดหมู่ผลงาน</label>
                    <input type="text" id="editPortCategory">
                </div>
                <div class="form-group">
                    <label>รูปภาพผลงาน (URL)</label>
                    <input type="url" id="editPortImage" oninput="document.getElementById('editPortPreview').src = this.value">
                    <img id="editPortPreview" src="" alt="Preview" class="modal-thumb-preview">
                </div>
                <div class="form-group">
                    <label>รายละเอียดผลงาน</label>
                    <textarea id="editPortDesc" rows="3"></textarea>
                </div>
                <div class="form-btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeAdminModal('editPortfolioModal')">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
