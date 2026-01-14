<?php
session_start();

// ✅ 1. ตรวจสอบสิทธิ์การเข้าถึง (Security Layer)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); 
    exit();
}

$admin_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'ผู้ดูแลระบบ';

// ✅ 2. ดึงข้อมูลพื้นฐานจากฐานข้อมูล
include '../config.php';
$sql_logo = "SELECT name_lb FROM tb_logobanner WHERE id_lb = 1";
$res_logo = mysqli_query($conn, $sql_logo);
$logo_row = mysqli_fetch_assoc($res_logo);
$logo_path = (!empty($logo_row['name_lb'])) ? "img_ad/".$logo_row['name_lb'] : "img_ad/LOGO3.png";
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TatoFun Admin - ระบบจัดการหลังบ้าน</title>
    <link rel="icon" type="image/png" href="<?= $logo_path ?>">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --tato-yellow: #ffca28;
            --tato-orange: #f57c00;
            --tato-dark: #263238;
            --tato-light: #fffdf0;
        }

        body { 
            font-family: 'Kanit', sans-serif; 
            background-color: var(--tato-light); 
            min-height: 100vh;
        }

        .navbar { 
            background-color: var(--tato-yellow) !important; 
            border-bottom: 3px solid var(--tato-orange);
            padding: 0.5rem 1rem;
        }

        .admin-card { 
            border: none; 
            border-radius: 25px; 
            transition: all 0.3s ease;
            background: #ffffff;
            height: 100%; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .admin-card:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1); 
        }

        .icon-circle {
            width: 80px; height: 80px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.2rem;
        }

        .info-box {
            background: white;
            border-left: 6px solid var(--tato-yellow);
            border-radius: 20px;
        }

        .btn-action {
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: 0.3s;
        }
        
        .dropdown-menu { border-radius: 15px; border: none; min-width: 180px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top shadow-sm">
        <div class="container-fluid"> 
            <a class="navbar-brand fw-bold d-flex align-items-center" href="index_ad.php">
                <img src="<?= $logo_path ?>" alt="Logo" width="45" height="45" class="me-2 rounded-circle bg-white shadow-sm p-1"> 
                <span class="fs-4 text-dark">TatoFun <span class="text-white">Admin</span></span>
            </a>
            
            <div class="ms-auto d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-dark rounded-pill px-3 dropdown-toggle fw-bold shadow-sm" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1 text-warning"></i> <?php echo $admin_name; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 p-2">
                        <li>
                            <a class="dropdown-item py-2" href="../index.php">
                                <i class="bi bi-shop me-2"></i>ไปหน้าร้านค้า
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item py-2 text-danger fw-bold" href="../logout.php">
                                <i class="bi bi-power me-2"></i>ออกจากระบบ
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold text-dark mb-2">ยินดีต้อนรับเข้าสู่ <span class="text-warning">Dashboard</span></h1>
            <div class="mx-auto bg-warning" style="height: 4px; width: 80px; border-radius: 2px;"></div>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card admin-card p-4 text-center border-top border-5 border-success">
                    <div class="card-body d-flex flex-column p-0">
                        <div class="icon-circle bg-success bg-opacity-10 text-success">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                        <h4 class="fw-bold">รายการสั่งซื้อ</h4>
                        <p class="text-muted small">ตรวจสอบออเดอร์ใหม่ ยืนยันการชำระเงิน และส่งของ</p>
                        <div class="mt-auto pt-4">
                            <a href="manage_orders.php" class="btn btn-success w-100 btn-action shadow-sm">
                                <i class="bi bi-cart-fill me-2"></i>ดูออเดอร์ทั้งหมด
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card admin-card p-4 text-center border-top border-5 border-warning">
                    <div class="card-body d-flex flex-column p-0">
                        <div class="icon-circle bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-egg-fried"></i>
                        </div>
                        <h4 class="fw-bold">จัดการเมนูอาหาร</h4>
                        <p class="text-muted small">เพิ่ม แก้ไข ลบรายการอาหาร และปรับราคา</p>
                        <div class="mt-auto pt-4">
                            <a href="manage_menu.php" class="btn btn-warning w-100 btn-action shadow-sm">
                                <i class="bi bi-gear-fill me-2"></i>เข้าสู่หน้าจัดการ
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card admin-card p-4 text-center border-top border-5 border-dark">
                    <div class="card-body d-flex flex-column p-0">
                        <div class="icon-circle bg-dark bg-opacity-10 text-dark">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h4 class="fw-bold">สรุปรายได้</h4>
                        <p class="text-muted small">ดูสถิติรายได้รายวัน และยอดขายสะสม</p>
                        <div class="mt-auto pt-4">
                            <a href="sales_report.php" class="btn btn-dark w-100 btn-action shadow-sm">
                                <i class="bi bi-file-earmark-bar-graph me-2"></i>ดูรายงานสถิติ
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card admin-card p-4 text-center border-top border-5 border-danger">
                    <div class="card-body d-flex flex-column p-0">
                        <div class="icon-circle bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-megaphone"></i>
                        </div>
                        <h4 class="fw-bold">จัดการโปรโมชั่น</h4>
                        <p class="text-muted small">สร้างแบนเนอร์ และกิจกรรมพิเศษหน้าแรก</p>
                        <div class="mt-auto pt-4">
                            <a href="manage_promotion.php" class="btn btn-danger w-100 btn-action shadow-sm text-white">
                                <i class="bi bi-percent me-2"></i>เข้าสู่หน้าจัดการ
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card admin-card p-4 text-center border-top border-5 border-primary">
                    <div class="card-body d-flex flex-column p-0">
                        <div class="icon-circle bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-image"></i>
                        </div>
                        <h4 class="fw-bold">โลโก้ & แบนเนอร์</h4>
                        <p class="text-muted small">เปลี่ยนรูปภาพสไลด์หน้าแรก และอัปเดตโลโก้</p>
                        <div class="mt-auto pt-4">
                            <a href="manage_logobanner.php" class="btn btn-primary w-100 btn-action shadow-sm">
                                <i class="bi bi-palette-fill me-2"></i>เข้าสู่หน้าจัดการ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 p-4 info-box shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h5 class="fw-bold mb-1 text-dark">
                        <i class="bi bi-shield-lock-fill me-2 text-warning"></i>ความปลอดภัยและการใช้งาน
                    </h5>
                    <p class="text-muted mb-0 small">
                        ทุกการเปลี่ยนแปลงจะส่งผลต่อหน้าร้านค้าออนไลน์ทันที 
                        <span class="text-danger fw-bold">กรุณาตรวจสอบข้อมูลก่อนกดบันทึกเสมอ</span>
                    </p>
                </div>
                <div class="col-md-3 text-md-end mt-3 mt-md-0">
                    <a href="../index.php" class="btn btn-outline-dark btn-sm rounded-pill px-4">
                        <i class="bi bi-eye me-1"></i> ดูหน้าร้านจริง
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>