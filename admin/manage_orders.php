<?php
session_start();
include '../config.php'; 

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// 1. ดึงรายการออเดอร์
$sql = "SELECT * FROM tb_orders ORDER BY order_id DESC"; 
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("เกิดข้อผิดพลาดในการดึงข้อมูล: " . mysqli_error($conn));
}

// 2. นับออเดอร์ที่ยังเป็น 'รอตรวจสอบ'
$sql_pending = "SELECT COUNT(*) as total FROM tb_orders WHERE order_status = 'รอตรวจสอบ'";
$res_pending = mysqli_query($conn, $sql_pending);
$pending_count = ($res_pending) ? mysqli_fetch_assoc($res_pending)['total'] : 0;
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการรายการสั่งซื้อ - TatoFun Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');
        body { background-color: #fffdf0; font-family: 'Kanit', sans-serif; }
        .order-card { border-radius: 25px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: #ffffff; }
        .status-badge { border-radius: 50px; padding: 5px 15px; font-weight: 600; font-size: 0.85rem; }
        .status-pending { background: #fff3cd; color: #856404; } 
        .status-shipping { background: #cfe2ff; color: #084298; } 
        .status-success { background: #d1e7dd; color: #0f5132; }  
        
        /* ตกแต่งปุ่มพิมพ์เพิ่มเติม */
        .btn-print { background-color: #0dcaf0; color: white; border: none; }
        .btn-print:hover { background-color: #0baccc; color: white; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-4 shadow-sm border-start border-success border-5">
        <h4 class="fw-bold text-success mb-0">
            <i class="bi bi-clipboard-check-fill me-2"></i> รายการสั่งซื้อ (Orders)
            <?php if($pending_count > 0): ?>
                <span class="badge bg-danger rounded-pill ms-2 fs-6"><?= $pending_count ?> ใหม่</span>
            <?php endif; ?>
        </h4>
        <a href="index_ad.php" class="btn btn-secondary btn-sm rounded-pill px-4 shadow-sm">← กลับหน้าหลัก</a>
    </div>

    <div class="card order-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-nowrap align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">ID</th>
                        <th>ข้อมูลลูกค้า</th>
                        <th class="text-end">ยอดรวม</th>
                        <th class="text-center">สถานะ</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="text-center fw-bold">#<?= $row['order_id'] ?></td>
                        <td>
                            <div class="fw-bold"><?= htmlspecialchars($row['cus_name'] ?? 'ไม่ระบุชื่อ') ?></div>
                            <small class="text-muted"><i class="bi bi-telephone"></i> <?= $row['cus_tel'] ?? '-' ?></small>
                        </td>
                        <td class="text-end fw-bold text-primary"><?= number_format($row['total_price'] ?? 0, 2) ?> ฿</td>
                        <td class="text-center">
                            <?php 
                                $s = $row['order_status'] ?? 'รอตรวจสอบ';
                                $class = 'status-pending';
                                if($s == 'กำลังส่ง') $class = 'status-shipping';
                                if($s == 'สำเร็จแล้ว') $class = 'status-success';
                            ?>
                            <span class="status-badge <?= $class ?>"><?= $s ?></span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="print_shipping.php?id=<?= $row['order_id'] ?>" target="_blank" class="btn btn-print btn-sm rounded-pill px-3 shadow-sm">
                                    <i class="bi bi-printer"></i> พิมพ์
                                </a>

                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm border rounded-pill dropdown-toggle shadow-sm" data-bs-toggle="dropdown">จัดการ</button>
                                    <ul class="dropdown-menu shadow border-0">
                                        <li><a class="dropdown-item" href="order_detail.php?id=<?= $row['order_id'] ?>"><i class="bi bi-eye"></i> ดูรายละเอียด</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-primary" href="update_status.php?id=<?= $row['order_id'] ?>&status=กำลังส่ง"><i class="bi bi-truck"></i> จัดส่งสินค้า</a></li>
                                        <li><a class="dropdown-item text-success" href="update_status.php?id=<?= $row['order_id'] ?>&status=สำเร็จแล้ว"><i class="bi bi-check-circle"></i> ทำรายการสำเร็จ</a></li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>