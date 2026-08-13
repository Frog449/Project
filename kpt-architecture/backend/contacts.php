<?php
require_once __DIR__ . '/db.php';

$contacts = [];
if ($pdo) {
    try {
        $contacts = $pdo->query("SELECT * FROM contacts ORDER BY id DESC")->fetchAll();
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPT Architecture | ข้อความสอบถาม (PHP Database Integrated)</title>
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
                <a href="users.php"><i class="fa-solid fa-users"></i> จัดการสมาชิก</a>
                <a href="contacts.php" class="active"><i class="fa-solid fa-inbox"></i> ข้อความสอบถาม</a>
                <a href="http://localhost:8082" target="_blank" class="highlight-link"><i class="fa-solid fa-database"></i> phpMyAdmin (8082)</a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <header class="topbar">
                <div class="topbar-title">
                    <h2>ข้อความสอบถามจากผู้เยี่ยมชมเว็บ (PHP Direct Database Inbox)</h2>
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
                        <div class="metric-icon orange"><i class="fa-solid fa-envelope"></i></div>
                        <div>
                            <div class="metric-val" id="statContacts"><?= count($contacts) ?></div>
                            <div class="metric-lbl">ข้อความสอบถามทั้งหมด</div>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon green"><i class="fa-solid fa-circle-check"></i></div>
                        <div>
                            <div class="metric-val">พร้อมตอบกลับ</div>
                            <div class="metric-lbl">ระบบส่งข้อมูลเข้า DB เรียบร้อย</div>
                        </div>
                    </div>
                </div>

                <!-- Contacts Log Section -->
                <div class="section-card">
                    <div class="card-header">
                        <h3><i class="fa-solid fa-inbox"></i> รายการข้อความติดต่อล่าสุด (PHP PDO Rendered)</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ชื่อผู้ติดต่อ</th>
                                    <th>อีเมล</th>
                                    <th>หัวข้อเรื่อง</th>
                                    <th>ข้อความ</th>
                                    <th>วันที่</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody id="contactsTable">
                                <?php if (empty($contacts)): ?>
                                    <tr><td colspan="7" style="text-align:center; color: var(--text-muted); padding:30px;">ไม่มีข้อความสอบถามในขณะนี้ใน Database</td></tr>
                                <?php else: ?>
                                    <?php foreach ($contacts as $c): ?>
                                        <tr>
                                            <td><?= $c['id'] ?></td>
                                            <td><strong><?= htmlspecialchars($c['name']) ?></strong></td>
                                            <td><?= htmlspecialchars($c['email']) ?></td>
                                            <td><?= htmlspecialchars($c['subject'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($c['message']) ?></td>
                                            <td><?= $c['created_at'] ? substr($c['created_at'], 0, 10) : '-' ?></td>
                                            <td>
                                                <button class="btn btn-danger btn-sm" onclick="deleteItem('contacts_delete', <?= $c['id'] ?>)"><i class="fa-solid fa-trash"></i> ลบ</button>
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

    <script src="script.js"></script>
</body>
</html>
