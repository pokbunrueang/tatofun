<?php
session_start();

// ✅ 1. ตรวจสอบสิทธิ์การเข้าถึง (Security Layer)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php"); 
    exit();
}

$admin_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'ผู้ดูแลระบบ';
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TatoFun Admin - ระบบจัดการหลังบ้าน</title>
    <link rel="icon" type="image/png" href="img_ad/LOGO3.png">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');
        
        body { 
            font-family: 'Kanit', sans-serif; 
            background-color: #fffdf0; 
            min-height: 100vh;
        }

        .navbar { 
            background-color: #ffc107; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
        }

        .admin-card { 
            border: none; 
            border-radius: 30px; 
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: #ffffff;
            height: 100%; 
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .admin-card:hover { 
            transform: translateY(-12px); 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); 
        }

        .icon-circle {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2.5rem;
        }

        .info-box {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-left: 6px solid #ffc107;
            border-radius: 20px;
        }

        .text-dark-yellow { color: #856404; }

        .btn-action {
            border-radius: 15px;
            padding: 10px 20px;
            font-weight: 600;
            transition: 0.3s;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top navbar-light">
        <div class="container"> 
            <a class="navbar-brand fw-bold d-flex align-items-center" href="index_ad.php">
                <img src="img_ad/LOGO3.png" alt="Logo" width="45" class="me-2"> 
                <span class="d-none d-sm-inline">TatoFun Admin</span>
            </a>
            
            <div class="ms-auto d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-link text-dark text-decoration-none dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> <?php echo $admin_name; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="border-radius: 15px;">
                        <li><a class="dropdown-item py-2" href="../index.php"><i class="bi bi-shop me-2"></i>ไปหน้าร้านค้า</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>ออกจากระบบ</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold text-dark-yellow mb-2"><i class="bi bi-stars me-2 text-warning"></i>ระบบจัดการหลังบ้าน</h1>
            <p class="text-muted">จัดการข้อมูลร้านค้า TatoFun ให้มีประสิทธิภาพ</p>
        </div>

        <div class="row g-4 justify-content-center">
            
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card admin-card p-4 text-center border-top border-5 border-success">
                    <div class="card-body d-flex flex-column p-0">
                        <div class="icon-circle bg-success bg-opacity-10 text-success">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                        <h4 class="fw-bold text-dark">รายการสั่งซื้อ</h4>
                        <p class="text-muted small px-2">ตรวจสอบออเดอร์ใหม่ ยืนยันการชำระเงิน และอัปเดตสถานะการส่ง</p>
                        <div class="mt-auto">
                            <hr class="my-4 opacity-25">
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
                        <h4 class="fw-bold text-dark">จัดการเมนูอาหาร</h4>
                        <p class="text-muted small px-2">เพิ่ม แก้ไข ลบรายการอาหาร และปรับปรุงราคาแบบ Real-time</p>
                        <div class="mt-auto">
                            <hr class="my-4 opacity-25">
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
                        <h4 class="fw-bold text-dark">สรุปรายได้</h4>
                        <p class="text-muted small px-2">ดูสถิติรายได้รายวัน และยอดขายสะสม</p>
                        <div class="mt-auto">
                            <hr class="my-4 opacity-25">
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
                        <h4 class="fw-bold text-dark">จัดการโปรโมชั่น</h4>
                        <p class="text-muted small px-2">สร้างแบนเนอร์ กิจกรรมพิเศษ และกำหนดช่วงเวลาลดราคา</p>
                        <div class="mt-auto">
                            <hr class="my-4 opacity-25">
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
                        <h4 class="fw-bold text-dark">โลโก้ & แบนเนอร์</h4>
                        <p class="text-muted small px-2">เปลี่ยนรูปภาพสไลด์หน้าแรก และอัปเดตโลโก้ร้านค้า</p>
                        <div class="mt-auto">
                            <hr class="my-4 opacity-25">
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
                <div class="col-md-8">
                    <h5 class="fw-bold mb-1 text-dark-yellow">
                        <i class="bi bi-shield-lock-fill me-2 text-warning"></i>ความปลอดภัยและการใช้งาน
                    </h5>
                    <p class="text-muted mb-0 small">
                        ทุกการเปลี่ยนแปลงจะส่งผลต่อหน้าเว็บหลักทันที 
                        <span class="text-danger fw-bold">กรุณาตรวจสอบรูปภาพและราคาก่อนกดบันทึก</span>
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="../index.php" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill px-4">
                        <i class="bi bi-eye me-1"></i> ดูหน้าร้านค้าออนไลน์
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>