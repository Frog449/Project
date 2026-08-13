<?php
require_once __DIR__ . '/db.php';

$statCourses = 0;
$statTeachers = 0;
$statPortfolios = 0;
$statContacts = 0;
$statUsers = 0;
$statNews = 0;

if ($pdo) {
    try {
        $statCourses = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn() ?: 0;
        $statTeachers = $pdo->query("SELECT COUNT(*) FROM teachers")->fetchColumn() ?: 0;
        $statPortfolios = $pdo->query("SELECT COUNT(*) FROM portfolios")->fetchColumn() ?: 0;
        $statContacts = $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn() ?: 0;
        $statUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() ?: 0;
        $statNews = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn() ?: 0;
    } catch (Exception $e) {
        // Fallback
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPT Architecture | ภาพรวมระบบ (PHP Database Integrated)</title>
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
                <a href="index.php" class="active"><i class="fa-solid fa-chart-pie"></i> ภาพรวมระบบ</a>
                <a href="info.php"><i class="fa-solid fa-circle-info"></i> ข้อมูลแผนกวิชา</a>
                <a href="courses.php"><i class="fa-solid fa-graduation-cap"></i> จัดการหลักสูตร</a>
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
                    <h2>ภาพรวมระบบผู้ดูแลระบบ (PHP MySQL Direct Connected)</h2>
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
                <!-- System Cards Overview (PHP Direct PDO) -->
                <div class="metrics-grid">
                    <div class="metric-card">
                        <div class="metric-icon blue"><i class="fa-solid fa-book-bookmark"></i></div>
                        <div>
                            <div class="metric-val" id="statCourses"><?= (int)$statCourses ?></div>
                            <div class="metric-lbl">หลักสูตรเปิดสอน</div>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-icon green"><i class="fa-solid fa-users"></i></div>
                        <div>
                            <div class="metric-val" id="statTeachers"><?= (int)$statTeachers ?></div>
                            <div class="metric-lbl">คณาจารย์ประจำ</div>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-icon purple"><i class="fa-solid fa-folder"></i></div>
                        <div>
                            <div class="metric-val" id="statPortfolios"><?= (int)$statPortfolios ?></div>
                            <div class="metric-lbl">ผลงานนักศึกษา</div>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-icon orange"><i class="fa-solid fa-envelope"></i></div>
                        <div>
                            <div class="metric-val" id="statContacts"><?= (int)$statContacts ?></div>
                            <div class="metric-lbl">ข้อความติดต่อเข้า</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Navigation Cards -->
                <div class="quick-nav-grid">
                    <a href="info.php" class="quick-card">
                        <i class="fa-solid fa-circle-info icon"></i>
                        <h4>ข้อมูลแผนกวิชา</h4>
                        <p>จัดการชื่อแผนก สโลแกน ประวัติ วิสัยทัศน์ และพันธกิจ</p>
                    </a>
                    <a href="courses.php" class="quick-card">
                        <i class="fa-solid fa-graduation-cap icon"></i>
                        <h4>จัดการหลักสูตร</h4>
                        <p>จัดการรายชื่อหลักสูตร ปวช. และ ปวส.</p>
                    </a>
                    <a href="teachers.php" class="quick-card">
                        <i class="fa-solid fa-user-group icon"></i>
                        <h4>จัดการคณาจารย์</h4>
                        <p>เพิ่ม แก้ไข และลบรายชื่อบุคลากรครู</p>
                    </a>
                    <a href="portfolios.php" class="quick-card">
                        <i class="fa-solid fa-folder-open icon"></i>
                        <h4>จัดการผลงานนักศึกษา</h4>
                        <p>จัดการคลังผลงานออกแบบ 3D & Model</p>
                    </a>
                    <a href="news.php" class="quick-card">
                        <i class="fa-solid fa-newspaper icon"></i>
                        <h4>จัดการข่าวประชาสัมพันธ์</h4>
                        <p>โพสต์ แก้ไข และลบข่าวสารและกิจกรรม</p>
                    </a>
                    <a href="users.php" class="quick-card">
                        <i class="fa-solid fa-users icon"></i>
                        <h4>จัดการสมาชิก</h4>
                        <p>ตรวจสอบ เพิ่ม แก้ไข และลบบัญชีผู้ใช้งานระบบ</p>
                    </a>
                </div>

                <!-- Container Architecture Live Status -->
                <div class="section-card">
                    <div class="card-header">
                        <h3><i class="fa-solid fa-cubes-stacked"></i> สถานะการเชื่อมต่อฐานข้อมูล PHP Backend (MySQL PDO)</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ส่วนการทำงาน</th>
                                    <th>โฮสต์ / คอนเทนเนอร์</th>
                                    <th>สถานะการเชื่อมต่อ PDO</th>
                                    <th>ตัวขับเคลื่อน (Driver)</th>
                                    <th>ชุดอักขระ (Charset)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>PHP Backend Server</strong></td>
                                    <td><code>backend</code> (Port 8081)</td>
                                    <td><span class="tag tag-green">PHP 8.2 Active</span></td>
                                    <td>Apache / PDO</td>
                                    <td>UTF-8</td>
                                </tr>
                                <tr class="active-row">
                                    <td><strong>Database Connection</strong></td>
                                    <td><code>mysql:3306</code></td>
                                    <td><span class="tag tag-green"><?= $pdo ? 'Connected Success' : 'Connection Failed' ?></span></td>
                                    <td>PDO MySQL (pdo_mysql)</td>
                                    <td>utf8mb4</td>
                                </tr>
                                <tr>
                                    <td><strong>Database Name</strong></td>
                                    <td><code>kpt_architecture</code></td>
                                    <td><span class="tag tag-green">Tables Ready</span></td>
                                    <td>InnoDB Engine</td>
                                    <td>utf8mb4_unicode_ci</td>
                                </tr>
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
