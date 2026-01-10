<?php
session_start();
include '../config.php'; 

// 1. ตรวจสอบสิทธิ์พนักงาน
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit();
}

// 2. ตรวจสอบ ID ออเดอร์
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('กรุณาเลือกออเดอร์ก่อน'); window.location='manage_orders.php';</script>";
    exit();
}

$order_id = mysqli_real_escape_string($conn, $_GET['id']);

// 3. ดึงข้อมูลรายการอาหาร (อ้างอิงโครงสร้างจริงใน db_tatofun.sql)
$sql = "SELECT m.name_menu, m.price_menu 
        FROM tb_order_details od
        JOIN tb_menu m ON od.menu_id = m.id_menu 
        WHERE od.order_id = '$order_id'";

$result = mysqli_query($conn, $sql);

// ตรวจสอบว่ามีข้อมูลไหม
if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('ไม่พบข้อมูลออเดอร์นี้'); window.location='manage_orders.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดออเดอร์ #<?php echo $order_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --tato-yellow: #ffca28;
            --tato-orange: #f57c00;
            --tato-dark: #2d3436;
            --tato-bg: #f8f9fa;
        }

        body { 
            font-family: 'Kanit', sans-serif; 
            background: var(--tato-bg); 
            min-height: 100vh; 
        }

        /* จัดกึ่งกลางเฉพาะตัวใบเสร็จ */
        .receipt-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }

        .receipt-card { 
            border: none; 
            border-radius: 30px; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.05); 
            overflow: hidden; 
            background: #fff; 
            max-width: 450px;
            width: 100%;
            position: relative;
        }

        .receipt-header { 
            background: linear-gradient(135deg, var(--tato-yellow), var(--tato-orange)); 
            padding: 30px; 
            text-align: center; 
            color: #000;
        }

        .total-box { 
            background: #fff9e6; 
            border-radius: 20px; 
            padding: 20px; 
            border: 2px dashed var(--tato-yellow); 
            margin-top: 20px;
        }

        .btn-back { 
            background: var(--tato-dark); 
            color: #fff; 
            border-radius: 50px; 
            padding: 10px; 
            width: 100%; 
            display: block; 
            text-align: center; 
            font-weight: 600;
            text-decoration: none;
            margin-top: 10px;
        }

        .btn-print {
            border: 2px solid var(--tato-orange);
            color: var(--tato-orange);
            border-radius: 50px;
            padding: 10px;
            width: 100%;
            display: block;
            text-align: center;
            font-weight: 600;
            text-decoration: none;
        }

        @media print {
            .container-fluid, .btn-back, .btn-print { display: none !important; }
            body { background: #fff; padding: 0; }
            .receipt-wrapper { padding: 0; }
            .receipt-card { box-shadow: none; border: 1px solid #ddd; }
        }
    </style>
</head>
<body>

<div class="container-fluid bg-white py-3 shadow-sm mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="manage_orders.php" class="btn btn-light rounded-pill px-3 shadow-sm border-0" style="background-color: #f8f9fa;">
            <i class="bi bi-arrow-left-circle me-1"></i> กลับไปรายการออเดอร์
        </a>
        <h4 class="fw-bold mb-0" style="color: #ffc107;">รายละเอียดออเดอร์</h4>
        <div style="width: 150px;" class="d-none d-md-block text-end small text-muted">
            #<?php echo $order_id; ?>
        </div>
    </div>
</div>

<div class="container receipt-wrapper">
    <div class="receipt-card">
        <div class="receipt-header">
            <i class="bi bi-receipt-cutoff fs-1"></i>
            <h3 class="fw-bold mb-0">TatoFun Fries</h3>
            <p class="mb-0 small">ใบแจ้งรายการอาหาร</p>
        </div>

        <div class="card-body p-4">
            <div class="d-flex justify-content-between mb-4 border-bottom pb-3">
                <div>
                    <span class="text-muted small">หมายเลข:</span>
                    <div class="fw-bold text-dark">#<?php echo $order_id; ?></div>
                </div>
                <div class="text-end">
                    <span class="text-muted small">วันที่:</span>
                    <div class="fw-bold text-dark"><?php echo date('d/m/Y'); ?></div>
                </div>
            </div>

            <table class="table table-borderless">
                <thead>
                    <tr class="border-bottom small text-muted">
                        <th>รายการอาหาร</th>
                        <th class="text-end">ราคา</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    while($item = mysqli_fetch_assoc($result)): 
                        $total += $item['price_menu'];
                    ?>
                    <tr>
                        <td class="py-2">
                            <i class="bi bi-check2-circle text-warning me-1"></i>
                            <?php echo htmlspecialchars($item['name_menu']); ?>
                        </td>
                        <td class="text-end py-2 fw-bold">฿<?php echo number_format($item['price_menu'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="total-box">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">ยอดรวมสุทธิ</span>
                    <span class="h3 fw-bold text-danger mb-0">฿<?php echo number_format($total, 2); ?></span>
                </div>
            </div>

            <div class="mt-4">
                <a href="javascript:window.print()" class="btn-print mb-2">
                    <i class="bi bi-printer me-2"></i> พิมพ์ใบเสร็จ
                </a>
                <a href="manage_orders.php" class="btn-back">
                    กลับไปจัดการออเดอร์
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>