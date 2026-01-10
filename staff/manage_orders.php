<?php
session_start();
include '../config.php'; 

// ตรวจสอบสิทธิ์ Staff
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit();
}

// ดึงข้อมูลออเดอร์จาก tb_orders โดยเรียงจากใหม่ไปเก่า
$sql = "SELECT * FROM tb_orders ORDER BY order_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการออเดอร์ - TatoFun Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Kanit', sans-serif; }
        /* สไตล์ Card สำหรับตาราง */
        .card-order { border: none; border-radius: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; }
        .empty-container { padding: 100px 20px; text-align: center; }
        .empty-icon { font-size: 80px; color: #ffca28; margin-bottom: 20px; opacity: 0.5; }
        .status-badge { border-radius: 50px; padding: 6px 15px; font-size: 0.85rem; font-weight: 600; }
        .text-orange { color: #f57c00; }
    </style>
</head>
<body>

<div class="container-fluid bg-white py-3 shadow-sm mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="index_st.php" class="btn btn-light rounded-pill px-3 shadow-sm border-0" style="background-color: #f8f9fa;">
            <i class="bi bi-arrow-left-circle me-1"></i> กลับหน้าหลัก
        </a>
        
        <h4 class="fw-bold mb-0" style="color: #ffc107;">
            <?php 
                $current_page = basename($_SERVER['PHP_SELF']);
                if($current_page == 'manage_orders.php') echo 'ระบบจัดการออเดอร์';
                elseif($current_page == 'view_stock.php') echo 'ระบบจัดการสต็อกสินค้า';
                elseif($current_page == 'finance_report.php') echo 'สรุปรายงานการเงิน';
            ?>
        </h4>
        
        <button class="btn btn-outline-warning rounded-pill px-3 border-0" onclick="location.reload()">
            <i class="bi bi-arrow-clockwise"></i> อัปเดต
        </button>
    </div>
</div>

<div class="container">
    <div class="card card-order bg-white p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0"><i class="bi bi-list-stars me-2 text-warning"></i>รายการออเดอร์ทั้งหมด</h5>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">เลขออเดอร์</th>
                            <th>วันที่สั่งซื้อ</th>
                            <th>ราคารวม</th>
                            <th>สถานะ</th>
                            <th class="text-center">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">#<?= $row['order_id'] ?></td>
                            <td><small><?= date('d/m/Y H:i', strtotime($row['order_date'])) ?></small></td>
                            <td class="fw-bold text-orange"><?= number_format($row['total_price'] ?? 0, 2) ?> ฿</td>
                            <td>
                                <?php 
                                    // กำหนดสี Badge ตามสถานะจากฐานข้อมูล
                                    $status = $row['order_status'];
                                    $status_class = ($status == 'pending') ? 'bg-warning text-dark' : 
                                                   (($status == 'cooking') ? 'bg-info text-white' : 'bg-success text-white');
                                ?>
                                <span class="badge status-badge <?= $status_class ?>">
                                    <?= strtoupper($status) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="order_details.php?id=<?= $row['order_id'] ?>" class="btn btn-warning btn-sm rounded-pill px-4 shadow-sm">
                                    ดูรายละเอียด
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-container">
                <i class="bi bi-clipboard2-x empty-icon"></i>
                <h3 class="fw-bold text-dark">ยังไม่มีออเดอร์เข้ามา</h3>
                <p class="text-muted">ขณะนี้ยังไม่มีรายการสั่งซื้อจากลูกค้าในระบบ</p>
                <a href="index_st.php" class="btn btn-warning px-5 py-2 mt-3 rounded-pill fw-bold shadow">
                    กลับไปหน้าแดชบอร์ด
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>