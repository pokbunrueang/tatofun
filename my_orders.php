<?php 
session_start();
include 'config.php';

// ตรวจสอบ Login (ใช้ชื่อ session ให้ตรงกับระบบของคุณ)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลออเดอร์พร้อมสถานะการส่ง (JOIN ตาราง shipping เพื่อเช็คสถานะสินค้า)
$sql = "SELECT o.*, s.ship_status 
        FROM tb_orders o 
        LEFT JOIN tb_shipping s ON o.order_id = s.order_id 
        WHERE o.user_id = '$user_id' 
        ORDER BY o.order_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการสั่งซื้อของฉัน - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kanit', sans-serif; background-color: #fdfaf0; color: #444; }
        .order-card { 
            border: none; border-radius: 24px; transition: all 0.3s ease; 
            background: #fff; overflow: hidden;
        }
        .order-card:hover { 
            transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important; 
        }
        .status-badge { 
            padding: 6px 16px; border-radius: 50px; font-weight: 600; 
            font-size: 0.8rem; display: inline-block;
        }
        .text-orange { color: #f57c00; }
        .bg-orange-light { background-color: #fff3e0; color: #ef6c00; }
        .btn-detail {
            border: 2px solid #eee; color: #666; font-weight: 600; transition: 0.2s;
        }
        .btn-detail:hover { background: #444; color: #fff; border-color: #444; }
        .order-id-label {
            background: #444; color: #fff; padding: 2px 10px; border-radius: 8px; font-size: 0.85rem;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div class="d-flex align-items-center">
            <a href="index.php" class="btn btn-white shadow-sm rounded-circle me-3" style="width:45px; height:45px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-chevron-left"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-0">รายการสั่งซื้อ<span class="text-orange">ของฉัน</span></h2>
                <p class="text-muted small mb-0">ติดตามสถานะออเดอร์ของคุณได้ที่นี่</p>
            </div>
        </div>
        <div class="text-end d-none d-md-block">
            <span class="badge bg-orange-light rounded-pill px-3 py-2">รวมทั้งหมด <?= mysqli_num_rows($result) ?> รายการ</span>
        </div>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="row g-4">
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-12">
                    <div class="card order-card shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <div class="mb-2">
                                        <span class="order-id-label">#<?= $row['order_id']; ?></span>
                                    </div>
                                    <div class="small text-muted">
                                        <i class="bi bi-calendar3 me-1"></i> <?= date('d M Y | H:i', strtotime($row['order_date'])); ?>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-3 mb-md-0 text-md-center">
                                    <div class="text-muted small mb-1">ยอดชำระ</div>
                                    <h5 class="fw-bold mb-0 text-orange">฿<?= number_format($row['total_price'], 2); ?></h5>
                                </div>

                                <div class="col-md-3 mb-3 mb-md-0 text-md-center">
                                    <div class="text-muted small mb-2">สถานะออเดอร์</div>
                                    <?php 
                                        $s = $row['order_status'];
                                        $bg = "bg-light text-muted";
                                        if($s == 'รอชำระเงิน') $bg = "bg-danger text-white";
                                        if($s == 'รอตรวจสอบชำระเงิน') $bg = "bg-warning text-dark";
                                        if($s == 'กำลังทำ') $bg = "bg-info text-white";
                                        if($s == 'สำเร็จ') $bg = "bg-success text-white";
                                    ?>
                                    <span class="status-badge <?= $bg; ?> shadow-sm">
                                        <i class="bi bi-dot"></i> <?= $s; ?>
                                    </span>
                                </div>

                                <div class="col-md-2 mb-3 mb-md-0 text-md-center border-start-md">
                                    <div class="text-muted small mb-1">การจัดส่ง</div>
                                    <span class="fw-bold d-block small">
                                        <?php 
                                            $ship = $row['ship_status'];
                                            if(!$ship) echo '<span class="text-muted fw-normal">รอดำเนินการ</span>';
                                            else if($ship == 'preparing') echo '<i class="bi bi-box-seam text-info"></i> เตรียมของ';
                                            else if($ship == 'shipping') echo '<i class="bi bi-truck text-primary"></i> ระหว่างส่ง';
                                            else if($ship == 'delivered') echo '<i class="bi bi-check2-all text-success"></i> ส่งถึงแล้ว';
                                        ?>
                                    </span>
                                </div>

                                <div class="col-md-2 text-md-end">
                                    <a href="order_detail.php?order_id=<?= $row['order_id']; ?>" 
                                       class="btn btn-detail rounded-pill btn-sm px-4 py-2 w-100 w-md-auto">
                                        ดูรายละเอียด
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5 bg-white rounded-5 shadow-sm mt-4">
            <img src="https://cdn-icons-png.flaticon.com/512/11329/11329073.png" style="width: 120px; opacity: 0.5;" class="mb-4">
            <h4 class="fw-bold text-muted">ไม่พบรายการสั่งซื้อ</h4>
            <p class="text-muted">คุณยังไม่มีประวัติการสั่งซื้อกับเราในตอนนี้</p>
            <a href="index.php" class="btn btn-warning rounded-pill fw-bold px-5 py-3 mt-3 shadow">
                เริ่มสั่งอาหารเลย <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>