<?php
session_start();
include 'config.php';

// 1. ตรวจสอบการ Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. ดึงข้อมูลแบบ JOIN 2 ตาราง (tb_users และ tb_customer_profile)
// เพื่อให้ได้ทั้ง username และข้อมูลโปรไฟล์ (รูป, เบอร์โทร, ที่อยู่)
$sql_user = "SELECT u.*, p.fullname, p.phone, p.address, p.user_img 
             FROM tb_users u 
             LEFT JOIN tb_customer_profile p ON u.user_id = p.user_id 
             WHERE u.user_id = '$user_id'";
             
$result_user = mysqli_query($conn, $sql_user);

if (!$result_user) {
    die("เกิดข้อผิดพลาดในการดึงข้อมูลสมาชิก: " . mysqli_error($conn));
}
$user = mysqli_fetch_assoc($result_user);

// 3. นับจำนวนออเดอร์ทั้งหมด
$sql_count = "SELECT COUNT(*) as total_orders FROM tb_orders WHERE user_id = '$user_id'";
$res_count = mysqli_query($conn, $sql_count);
$count_data = mysqli_fetch_assoc($res_count);
$total_orders = $count_data['total_orders'] ?? 0;

// 4. จัดการเรื่องรูปโปรไฟล์
$user_img = $user['user_img'] ?? '';
$profile_path = "uploads/profile/" . $user_img;
$default_img = "https://cdn-icons-png.flaticon.com/512/1144/1144760.png";

// เช็คว่ามีไฟล์รูปในโฟลเดอร์จริงๆ หรือไม่
$display_img = (!empty($user_img) && file_exists($profile_path)) ? $profile_path : $default_img;
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ของฉัน - TatoFun</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --tato-orange: #f57c00;
            --tato-yellow: #ffb300;
            --tato-bg: #fdfaf0;
        }
        body { font-family: 'Kanit', sans-serif; background-color: var(--tato-bg); color: #444; }
        
        .profile-header {
            background: linear-gradient(135deg, var(--tato-orange), var(--tato-yellow));
            height: 180px;
            border-radius: 0 0 60px 60px;
            box-shadow: 0 10px 20px rgba(245, 124, 0, 0.15);
        }

        .profile-img-wrapper {
            margin-top: -70px;
            position: relative;
            display: inline-block;
            z-index: 1;
        }
        .profile-img {
            width: 140px; height: 140px;
            border-radius: 50%; border: 6px solid #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            background: #fff; object-fit: cover;
            position: relative; z-index: 2;
            transition: transform 0.3s ease;
        }
        .profile-img:hover { transform: scale(1.03); }

        .img-glow {
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 150px; height: 150px;
            background: linear-gradient(135deg, var(--tato-orange), var(--tato-yellow));
            filter: blur(20px); opacity: 0.3;
            border-radius: 50%; z-index: 1;
        }

        .camera-btn {
            position: absolute; bottom: 5px; right: 5px;
            background: #fff; width: 40px; height: 40px;
            border-radius: 50%; display: flex;
            align-items: center; justify-content: center;
            color: var(--tato-orange); text-decoration: none;
            border: 3px solid #fdfaf0; z-index: 3;
            transition: all 0.2s ease;
        }
        .camera-btn:hover { background: var(--tato-orange); color: #fff; transform: scale(1.1); }

        .card-cute {
            border: none; border-radius: 25px;
            background: #ffffff; transition: all 0.3s ease;
        }
        .card-cute:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.05) !important; }
        
        .btn-tato {
            background: linear-gradient(to right, var(--tato-orange), var(--tato-yellow));
            color: white; border: none; border-radius: 50px; font-weight: 600;
            transition: 0.3s;
        }
        .btn-tato:hover { opacity: 0.9; color: white; }
        .info-label { font-size: 0.85rem; color: #aaa; margin-bottom: 2px; }
        .nav-link-item {
            display: flex; align-items: center; padding: 15px 20px;
            text-decoration: none; color: #555; background: #fff;
            border-radius: 15px; margin-bottom: 10px; transition: 0.3s;
        }
        .nav-link-item:hover { background: #fff3e0; color: var(--tato-orange); padding-left: 25px; }
    </style>
</head>
<body>

<div class="profile-header">
    <div class="container d-flex justify-content-between align-items-center pt-3">
        <a href="index.php" class="btn btn-light btn-sm rounded-pill px-3 shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> กลับหน้าหลัก
        </a>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <div class="text-center mb-5">
                <div class="profile-img-wrapper">
                    <div class="img-glow"></div> 
                    <img src="<?= $display_img ?>?v=<?= time() ?>" class="profile-img">
                    
                    <a href="edit_profile.php" class="camera-btn shadow-sm" title="เปลี่ยนรูปโปรไฟล์">
                        <i class="bi bi-camera-fill"></i>
                    </a>
                </div>
                
                <h2 class="fw-bold mt-4 mb-0"><?= htmlspecialchars($user['fullname'] ?? 'สมาชิกนิรนาม') ?></h2>
                <div class="d-flex justify-content-center align-items-center mt-2">
                    <span class="badge rounded-pill px-3 py-2 shadow-sm" style="background: rgba(245, 124, 0, 0.1); color: var(--tato-orange);">
                        <i class="bi bi-patch-check-fill me-1"></i> TatoFun Member
                    </span>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-7">
                    <div class="card card-cute shadow-sm p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">🏠 ข้อมูลที่อยู่และการติดต่อ</h5>
                            <a href="edit_profile.php" class="btn btn-sm btn-outline-warning rounded-pill px-3">แก้ไข</a>
                        </div>
                        <div class="mb-3">
                            <div class="info-label">ชื่อผู้ใช้งาน</div>
                            <div class="fw-bold"><?= htmlspecialchars($user['username'] ?? '-') ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label">เบอร์โทรศัพท์</div>
                            <div class="fw-bold"><?= htmlspecialchars($user['phone'] ?? '-') ?></div>
                        </div>
                        <div class="mb-0">
                            <div class="info-label">ที่อยู่ปัจจุบัน</div>
                            <div class="p-3 bg-light rounded-4 mt-2 border-start border-warning border-4 shadow-sm">
                                <?= nl2br(htmlspecialchars($user['address'] ?? 'ยังไม่ได้ระบุที่อยู่')) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card card-cute shadow-sm p-4 text-center mb-4 border-0">
                        <div class="bg-warning bg-opacity-10 rounded-4 py-4">
                            <i class="bi bi-egg-fried fs-1 text-warning"></i>
                            <h3 class="fw-bold mt-2 mb-0"><?= $total_orders ?></h3>
                            <p class="text-muted small mb-0">เมนูที่เคยสั่งกับเรา</p>
                        </div>
                        <a href="my_orders.php" class="btn btn-tato w-100 mt-3 py-2 shadow-sm">
                            ดูประวัติการสั่งซื้อ
                        </a>
                    </div>

                    <div class="nav-menu">
                        <a href="change_password.php" class="nav-link-item shadow-sm">
                            <i class="bi bi-shield-lock me-3 fs-5"></i>
                            <span>เปลี่ยนรหัสผ่าน</span>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                        <a href="logout.php" class="nav-link-item shadow-sm text-danger">
                            <i class="bi bi-box-arrow-right me-3 fs-5"></i>
                            <span>ออกจากระบบ</span>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php if(isset($_SESSION['user_id'])): ?>
    <div class="card shadow-sm border-0 rounded-4 p-4 text-center">
        <div class="mb-2 text-warning fs-1 border rounded-circle d-inline-block px-3">🍳</div>
        <h4 class="fw-bold">4</h4>
        <p class="text-muted small">เมนูที่เคยสั่งกับเรา</p>
        
        <a href="order_history.php" class="btn btn-warning w-100 rounded-pill fw-bold text-white py-2">
            ดูประวัติการสั่งซื้อ
        </a>
    </div>
<?php endif; ?>