<?php
session_start();

// 1. ตรวจสอบสิทธิ์พนักงาน (Security Check)
// ถ้าไม่มี Role หรือ Role ไม่ใช่ staff ให้เด้งกลับไปหน้า login
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit();
}

include '../config.php'; 
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ระบบพนักงาน - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --tato-yellow: #ffca28;
            --tato-orange: #f57c00;
            --tato-red: #ee5253;
            --tato-green: #10ac84;
            --tato-dark: #2d3436;
            --tato-bg: #f4f7f6;
        }

        body { 
            font-family: 'Kanit', sans-serif; 
            background-color: var(--tato-bg); 
            min-height: 100vh;
        }

        .top-bar { 
            background: #ffffff; 
            padding: 12px 0; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-bottom: 3px solid var(--tato-yellow);
        }

        .menu-card { 
            background: white; 
            border-radius: 30px; 
            border: none; 
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%; 
            display: flex; 
            flex-direction: column; 
            overflow: hidden;
            position: relative;
        }

        .menu-card:hover { 
            transform: translateY(-12px); 
        }

        .icon-circle {
            width: 90px;
            height: 90px;
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.5rem;
            transition: 0.3s;
        }

        .menu-card:hover .icon-circle {
            transform: scale(1.1) rotate(5deg);
        }

        .card-order:hover { box-shadow: 0 20px 40px rgba(255, 202, 40, 0.2); }
        .card-stock:hover { box-shadow: 0 20px 40px rgba(238, 82, 83, 0.2); }
        .card-finance:hover { box-shadow: 0 20px 40px rgba(16, 172, 132, 0.2); }

        .btn-staff {
            border-radius: 15px;
            padding: 12px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }

        .welcome-section {
            background: linear-gradient(135deg, var(--tato-dark), #485460);
            border-radius: 30px;
            color: white;
            padding: 40px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .welcome-section::after {
            content: 'Tato';
            position: absolute;
            right: -20px;
            bottom: -20px;
            font-size: 10rem;
            font-weight: 800;
            color: rgba(255,255,255,0.03);
        }
    </style>
</head>
<body>

    <div class="top-bar mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="../img/Logo.png" alt="Logo" width="40" class="me-2" onerror="this.src='https://via.placeholder.com/40?text=T'"> 
                <h5 class="fw-bold mb-0">TatoFun <span class="text-warning">Staff</span></h5>
            </div>
            <div class="d-flex align-items-center">
                <div class="me-3 text-end d-none d-md-block">
                    <div class="small text-muted">พนักงาน:</div>
                    <strong class="text-dark"><?php echo $_SESSION['fullname']; ?></strong>
                </div>
                <a href="../logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-4 fw-bold shadow-sm">
                    <i class="bi bi-power me-1"></i> ออกจากระบบ
                </a>
            </div>
        </div>
    </div>

    <div class="container py-3">
        <div class="welcome-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-2">ยินดีต้อนรับ,  <?php echo $_SESSION['fullname']; ?>! 👋</h2>
                    <p class="lead mb-0 opacity-75">จัดการร้าน TatoFun ของเราให้สนุกและเป็นระเบียบกันเถอะ</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="h2 mb-0 fw-bold" id="liveTime">00:00:00</div>
                    <div class="small opacity-50">เวลาปัจจุบัน</div>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            
            <div class="col-md-4">
                <div class="menu-card card-order p-5 text-center shadow-sm">
                    <div class="icon-circle bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>
                    <h4 class="fw-bold">จัดการออเดอร์</h4>
                    <p class="text-muted small">รับรายการอาหาร และอัปเดตสถานะการปรุง</p>
                    <a href="manage_orders.php" class="btn btn-warning btn-staff w-100 mt-auto shadow-sm">
                        เปิดดูรายการ
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="menu-card card-stock p-5 text-center shadow-sm">
                    <div class="icon-circle bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <h4 class="fw-bold">จัดการสต็อก</h4>
                    <p class="text-muted small">ตรวจสอบและจัดการความพร้อมของเมนู</p>
                    <a href="view_stock.php" class="btn btn-danger btn-staff w-100 mt-auto shadow-sm text-white">
                        เช็คสต็อก
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="menu-card card-finance p-5 text-center shadow-sm">
                    <div class="icon-circle bg-success bg-opacity-10 text-success">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <h4 class="fw-bold">ดูยอดขาย</h4>
                    <p class="text-muted small">ดูภาพรวมรายได้และจำนวนจานที่ขายได้</p>
                    <a href="finance_report.php" class="btn btn-success btn-staff w-100 mt-auto shadow-sm text-white">
                        ดูยอดขายวันนี้
                    </a>
                </div>
            </div>

        </div>

        <div class="text-center mt-5">
            <p class="text-muted small">© 2026 TatoFun System - อร่อยในทุกคำที่ได้ลอง</p>
        </div>
    </div>

    <script>
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('th-TH');
            document.getElementById('liveTime').textContent = timeString;
        }
        setInterval(updateTime, 1000);
        updateTime();
    </script>
</body>
</html>