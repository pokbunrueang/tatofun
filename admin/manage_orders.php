<?php 
session_start();
include '../config.php'; 

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// 1. ดึงข้อมูลออเดอร์: เรียง 'รอตรวจสอบชำระเงิน' ขึ้นก่อน ตามด้วยวันที่ล่าสุด
$sql = "SELECT * FROM tb_orders ORDER BY 
        CASE WHEN order_status = 'รอตรวจสอบชำระเงิน' THEN 1 ELSE 2 END, 
        order_date DESC"; 
$result = mysqli_query($conn, $sql);

// 2. ดึงสถิติ Dashboard (คำนวณยอดขายโดยไม่รวมรายการที่ยกเลิก)
$count_sql = "SELECT 
    COUNT(CASE WHEN order_status = 'รอตรวจสอบชำระเงิน' THEN 1 END) as pending,
    COUNT(CASE WHEN order_status = 'กำลังส่ง' THEN 1 END) as shipping,
    SUM(CASE WHEN order_status != 'ยกเลิกแล้ว' THEN total_price ELSE 0 END) as total_sales
    FROM tb_orders";

$res_count = mysqli_query($conn, $count_sql);
if ($res_count) {
    $stats = mysqli_fetch_assoc($res_count);
} else {
    die("SQL Error: " . mysqli_error($conn));
}
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการออเดอร์ - TatoFun Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Kanit', sans-serif; color: #444; }
        .main-card { border-radius: 20px; border: none; box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.05); }
        .stat-card { border: none; border-radius: 15px; transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .status-badge { border-radius: 50px; padding: 6px 14px; font-weight: 600; font-size: 0.75rem; }
        
        /* สีสถานะ */
        .bg-pending { background: #fff3cd; color: #856404; }
        .bg-processing { background: #e0f7fa; color: #006064; }
        .bg-shipping { background: #e8eaf6; color: #1a237e; }
        .bg-success-custom { background: #e8f5e9; color: #1b5e20; }
        .bg-cancelled { background: #f8d7da; color: #842029; } /* สีแดงสำหรับยกเลิก */

        .slip-thumb { 
            width: 50px; height: 50px; object-fit: cover; border-radius: 8px; 
            cursor: pointer; transition: 0.2s; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .slip-thumb:hover { transform: scale(1.1); z-index: 10; }
        .btn-action { border-radius: 10px; font-weight: 500; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">จัดการ<span class="text-primary">คำสั่งซื้อ</span></h2>
            <p class="text-muted mb-0">แผงควบคุมรายการอาหารและสถานะการชำระเงิน</p>
        </div>
        <div class="d-flex gap-2">
            <a href="index_ad.php" class="btn btn-white shadow-sm rounded-pill px-4">
                <i class="bi bi-house-door me-2"></i>หน้าหลัก
            </a>
            <a href="sales_report.php" class="btn btn-primary rounded-pill px-4 shadow">
                <i class="bi bi-graph-up-arrow me-2"></i>รายงานยอดขาย
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card shadow-sm p-3 bg-white border-start border-warning border-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">รอตรวจสอบชำระเงิน</div>
                        <h3 class="fw-bold mb-0"><?= number_format($stats['pending'] ?? 0) ?> รายการ</h3>
                    </div>
                    <div class="icon-shape bg-warning text-white p-3 rounded-circle"><i class="bi bi-clock-history fs-4"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-sm p-3 bg-white border-start border-info border-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">กำลังดำเนินการจัดส่ง</div>
                        <h3 class="fw-bold mb-0"><?= number_format($stats['shipping'] ?? 0) ?> รายการ</h3>
                    </div>
                    <div class="icon-shape bg-info text-white p-3 rounded-circle"><i class="bi bi-truck fs-4"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-sm p-3 bg-white border-start border-success border-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">ยอดขาย (ไม่รวมรายการยกเลิก)</div>
                        <h3 class="fw-bold mb-0">฿<?= number_format($stats['total_sales'] ?? 0, 2) ?></h3>
                    </div>
                    <div class="icon-shape bg-success text-white p-3 rounded-circle"><i class="bi bi-currency-dollar fs-4"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card main-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ออเดอร์</th>
                        <th>ข้อมูลลูกค้า</th>
                        <th>สลิป</th>
                        <th class="text-end">ยอดชำระ</th>
                        <th class="text-center">สถานะ</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark">#<?= $row['order_id'] ?></span>
                                <div class="small text-muted"><?= date('d/m/Y H:i', strtotime($row['order_date'])) ?></div>
                            </td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($row['cus_name']) ?></div>
                                <div class="small text-muted"><i class="bi bi-telephone me-1"></i><?= $row['cus_tel'] ?></div>
                            </td>
                            <td>
                                <?php if(!empty($row['slip_img'])): ?>
                                    <a href="img_slip/<?= $row['slip_img'] ?>" target="_blank">
                                        <img src="img_slip/<?= $row['slip_img'] ?>" class="slip-thumb" title="คลิกเพื่อดูรูปใหญ่">
                                    </a>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted fw-normal border">ไม่มีสลิป</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end fw-bold text-primary">฿<?= number_format($row['total_price'], 2) ?></td>
                            <td class="text-center">
                                <?php 
                                    $s = $row['order_status'];
                                    $badge_class = match($s) {
                                        'รอตรวจสอบชำระเงิน' => "bg-pending",
                                        'กำลังทำ' => "bg-processing",
                                        'กำลังส่ง' => "bg-shipping",
                                        'สำเร็จแล้ว' => "bg-success-custom",
                                        'ยกเลิกแล้ว' => "bg-cancelled",
                                        default => "bg-secondary text-white"
                                    };
                                ?>
                                <span class="status-badge <?= $badge_class ?>"><?= $s ?></span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-1">
                                    <?php if($row['order_status'] == 'รอตรวจสอบชำระเงิน'): ?>
                                        <a href="update_status.php?id=<?= $row['order_id'] ?>&status=กำลังทำ" 
                                           class="btn btn-success btn-sm btn-action px-3" 
                                           onclick="return confirm('ยืนยันว่าได้รับยอดเงินถูกต้อง?')">ยืนยันเงิน</a>
                                    <?php endif; ?>

                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm btn-action border dropdown-toggle" data-bs-toggle="dropdown">ตัวเลือก</button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li><a class="dropdown-item py-2" href="order_detail.php?id=<?= $row['order_id'] ?>"><i class="bi bi-eye me-2 text-primary"></i>รายละเอียด</a></li>
                                            <li><a class="dropdown-item py-2 text-info" href="../receipt_print.php?id=<?= $row['order_id'] ?>" target="_blank"><i class="bi bi-printer me-2"></i>พิมพ์ใบเสร็จ</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item py-2 text-warning" href="update_status.php?id=<?= $row['order_id'] ?>&status=กำลังส่ง"><i class="bi bi-truck me-2"></i>จัดส่งสินค้า</a></li>
                                            <li><a class="dropdown-item py-2 text-success" href="update_status.php?id=<?= $row['order_id'] ?>&status=สำเร็จแล้ว"><i class="bi bi-check-circle me-2"></i>สำเร็จรายการ</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item py-2 text-danger" 
                                                   href="update_status.php?id=<?= $row['order_id'] ?>&status=ยกเลิกแล้ว" 
                                                   onclick="return confirm('⚠️ คุณแน่ใจใช่ไหมที่จะยกเลิกออเดอร์ #<?= $row['order_id'] ?>?')">
                                                    <i class="bi bi-trash3 me-2"></i>ยกเลิกออเดอร์
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                ยังไม่มีรายการสั่งซื้อในระบบ
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>