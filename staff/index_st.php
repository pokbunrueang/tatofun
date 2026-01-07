<!--หน้า index พนักงานเสร็จละ เหลือทำหน้า หน้าที่-->
<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบพนักงาน - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Kanit', sans-serif; 
            background-color: #f4f7f6; /* สีเทาอมฟ้าอ่อนๆ ดูสะอาดตา */
            margin: 0;
        }
        
        /* แถบด้านบนดีไซน์ใหม่ */
        .top-bar {
            background: #ffffff;
            padding: 15px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        /* การ์ดเมนูแบบเน้นความชัดเจน */
        .menu-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .card-icon-area {
            padding: 30px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-shape {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        /* สีตามธีมเดิมที่คุณชอบ */
        .color-order { background-color: #fff9db; color: #fab005; border-bottom: 4px solid #fab005; }
        .color-stock { background-color: #fff5f5; color: #fa5252; border-bottom: 4px solid #fa5252; }
        .color-rider { background-color: #e7f5ff; color: #228be6; border-bottom: 4px solid #228be6; }

        .btn-action {
            border-radius: 12px;
            font-weight: 600;
            padding: 10px;
            margin-top: 15px;
        }

        .info-box {
            background: #ffffff;
            border-radius: 15px;
            padding: 20px;
            border-left: 5px solid #ffb300;
        }
    </style>
</head>
<body>

    <div class="top-bar mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="../admin/img_ad/LOGO3.png" alt="Logo" width="40" class="me-2">
                <h5 class="fw-bold mb-0">TatoFun <span class="text-warning">Staff</span></h5>
            </div>
            <div class="d-flex align-items-center">
                <span class="small me-3">ผู้ใช้งาน: <strong><?php echo $_SESSION['fullname']; ?> (สต๊าฟ)</strong></span>
                <a href="../logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">ออกจากระบบ</a>
            </div>
        </div>
    </div>

    <div class="container text-center pt-2">
        <h3 class="fw-bold text-dark">✨ ระบบจัดการพนักงาน</h3>
        <p class="text-muted small">ยินดีต้อนรับเข้าสู่ระบบจัดการ TatoFun สำหรับพนักงาน</p>

        <div class="row mt-5 g-4">
            <div class="col-md-4">
                <div class="menu-card color-order p-4">
                    <div class="card-icon-area">
                        <div class="icon-shape" style="background: rgba(250, 176, 5, 0.1);">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold">จัดการออเดอร์</h5>
                    <p class="text-muted small">ดูรายการสั่งซื้อ ตรวจสอบรายละเอียด และเตรียมทอดมันฝรั่ง</p>
                    <a href="manage_orders.php" class="btn btn-warning btn-action w-100 shadow-sm">ดูออเดอร์ทั้งหมด</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="menu-card color-stock p-4">
                    <div class="card-icon-area">
                        <div class="icon-shape" style="background: rgba(250, 82, 82, 0.1);">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold">สต็อกวัตถุดิบ</h5>
                    <p class="text-muted small">เช็คจำนวนมันฝรั่ง ซอส และอุปกรณ์ต่างๆ ว่าพอขายหรือไม่</p>
                    <a href="view_stock.php" class="btn btn-danger btn-action w-100 shadow-sm">ดูรายการสต็อก</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="menu-card color-rider p-4">
                    <div class="card-icon-area">
                        <div class="icon-shape" style="background: rgba(34, 139, 230, 0.1);">
                            <i class="bi bi-bicycle"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold">แจ้งสถานะไรเดอร์</h5>
                    <p class="text-muted small">อัปเดตสถานะอาหาร (กำลังทำ/เสร็จแล้ว) เพื่อแจ้งคนขับ</p>
                    <a href="rider_status.php" class="btn btn-primary btn-action w-100 shadow-sm">อัปเดตสถานะ</a>
                </div>
            </div>
        </div>

        <div class="mt-5 info-box text-start shadow-sm">
            <h6 class="fw-bold"><i class="bi bi-info-circle-fill text-warning"></i> คำแนะนำในการใช้งานสำหรับพนักงาน</h6>
            <ul class="small text-muted mb-0 mt-2">
                <li>กรุณากดรับออเดอร์ทันทีเมื่อมีรายการใหม่เข้ามาเพื่อให้ลูกค้าไม่ต้องรอนาน</li>
                <li>ตรวจสอบจำนวนวัตถุดิบหลัก (มันฝรั่ง/ชีส) ทุกๆ 2 ชั่วโมง</li>
                <li>เมื่ออาหารเสร็จแล้ว อย่าลืมกดเปลี่ยนสถานะเพื่อให้ไรเดอร์มารับของได้ถูกต้อง</li>
            </ul>
        </div>
    </div>

</body>
</html>