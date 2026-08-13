<?php
require_once __DIR__ . '/db.php';

$message = '';
$msgType = 'success';

// Handle Direct PHP Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name_th = trim($_POST['name_th'] ?? '');
    $name_en = trim($_POST['name_en'] ?? '');
    $college_th = trim($_POST['college_th'] ?? '');
    $college_en = trim($_POST['college_en'] ?? '');
    $slogan = trim($_POST['slogan'] ?? '');
    $history = trim($_POST['history'] ?? '');
    $vision = trim($_POST['vision'] ?? '');
    $mission = trim($_POST['mission'] ?? '');
    $established_year = (int)($_POST['established_year'] ?? 2537);

    if ($pdo) {
        try {
            $stmt = $pdo->prepare("UPDATE department_info SET name_th = ?, name_en = ?, college_th = ?, college_en = ?, slogan = ?, history = ?, vision = ?, mission = ?, established_year = ? WHERE id = 1");
            $stmt->execute([$name_th, $name_en, $college_th, $college_en, $slogan, $history, $vision, $mission, $established_year]);
            $message = 'บันทึกข้อมูลแผนกวิชาเข้า Database เรียบร้อยแล้ว (PHP PDO)';
        } catch (Exception $e) {
            $message = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage();
            $msgType = 'error';
        }
    }
}

// Fetch Current Info directly from Database using PHP PDO
$info = [
    'name_th' => 'แผนกวิชาสถาปัตยกรรม',
    'name_en' => 'Department of Architecture',
    'college_th' => 'วิทยาลัยเทคนิคกาญจนาภิเษก',
    'college_en' => 'Kanchanaphisek Technical College',
    'slogan' => 'สร้างสรรค์พื้นที่ด้วยจินตนาการ ก่อร่างสร้างอนาคตด้วยสถาปัตยกรรม',
    'history' => 'แผนกวิชาสถาปัตยกรรม มุ่งเน้นการจัดการเรียนการสอนเพื่อพัฒนาบุคลากรด้านการออกแบบสถาปัตยกรรม การเขียนแบบ นวัตกรรมอาคาร (BIM) และเทคโนโลยีก่อสร้างยุคใหม่',
    'vision' => 'เป็นผู้นำด้านการศึกษาและฝึกทักษะวิชาชีพสถาปัตยกรรมนวัตกรรม ตอบสนองความต้องการอุตสาหกรรมสร้างสรรค์และก่อสร้างอย่างยั่งยืน',
    'mission' => "1. จัดการเรียนการสอนเน้นการปฏิบัติจริงในห้องปฏิบัติการและสตูดิโอออกแบบ\n2. ส่งเสริมทักษะเทคโนโลยีคอมพิวเตอร์ช่วยออกแบบ (BIM, CAD, 3D Rendering)\n3. ผลิตกำลังคนที่มีคุณธรรม จริยธรรม และจรรยาบรรณวิชาชีพ",
    'established_year' => 2537
];

if ($pdo) {
    try {
        $dbInfo = $pdo->query("SELECT * FROM department_info WHERE id = 1")->fetch();
        if ($dbInfo) {
            $info = array_merge($info, $dbInfo);
        }
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPT Architecture | ข้อมูลแผนกวิชา (PHP Database Integrated)</title>
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
                <a href="info.php" class="active"><i class="fa-solid fa-circle-info"></i> ข้อมูลแผนกวิชา</a>
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
                    <h2>จัดการข้อมูลทั่วไปแผนกวิชา (PHP PDO Direct Query)</h2>
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

                <div class="section-card" style="padding: 35px;">
                    <div class="card-header" style="margin: -35px -35px 30px -35px;">
                        <h3><i class="fa-solid fa-landmark"></i> การตั้งค่าข้อมูลแผนกวิชา สโลแกน ประวัติ วิสัยทัศน์ และพันธกิจ (PHP Form)</h3>
                    </div>

                    <form action="info.php" method="POST" id="infoForm">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>ชื่อแผนกวิชา (ภาษาไทย)</label>
                                <input type="text" name="name_th" id="infoNameTh" required value="<?= htmlspecialchars($info['name_th']) ?>">
                            </div>
                            <div class="form-group">
                                <label>ชื่อแผนกวิชา (English)</label>
                                <input type="text" name="name_en" id="infoNameEn" required value="<?= htmlspecialchars($info['name_en']) ?>">
                            </div>
                            <div class="form-group">
                                <label>ชื่อวิทยาลัย (ภาษาไทย)</label>
                                <input type="text" name="college_th" id="infoCollegeTh" required value="<?= htmlspecialchars($info['college_th']) ?>">
                            </div>
                            <div class="form-group">
                                <label>ชื่อวิทยาลัย (English)</label>
                                <input type="text" name="college_en" id="infoCollegeEn" required value="<?= htmlspecialchars($info['college_en']) ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>สโลแกนแผนกวิชา (Slogan)</label>
                            <input type="text" name="slogan" id="infoSlogan" value="<?= htmlspecialchars($info['slogan']) ?>">
                        </div>

                        <div class="form-group">
                            <label>ประวัติความเป็นมา (History)</label>
                            <textarea name="history" id="infoHistory" rows="3"><?= htmlspecialchars($info['history']) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>วิสัยทัศน์ (Vision)</label>
                            <textarea name="vision" id="infoVision" rows="3"><?= htmlspecialchars($info['vision']) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>พันธกิจ (Mission)</label>
                            <textarea name="mission" id="infoMission" rows="4"><?= htmlspecialchars($info['mission']) ?></textarea>
                        </div>

                        <div class="form-group" style="max-width: 250px;">
                            <label>ปีที่ก่อตั้ง (พ.ศ.)</label>
                            <input type="number" name="established_year" id="infoEstYear" value="<?= (int)$info['established_year'] ?>">
                        </div>

                        <div style="margin-top: 30px; text-align: right;">
                            <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-size: 1rem;"><i class="fa-solid fa-save"></i> บันทึกการเปลี่ยนแปลง (PHP PDO)</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="script.js"></script>
</body>
</html>
