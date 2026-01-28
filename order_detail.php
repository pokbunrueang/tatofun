<?php 
session_start();
include 'config.php';

// 1. ตรวจสอบการ Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    header("Location: my_orders.php");
    exit();
}

$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);
$user_id = $_SESSION['user_id'];

// 2. ดึงข้อมูลออเดอร์ (เช็ค user_id เพื่อป้องกันคนอื่นแอบดูออเดอร์กัน)
$sql = "SELECT * FROM tb_orders WHERE order_id = '$order_id' AND user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    echo "<script>alert('ไม่พบข้อมูลออเดอร์'); window.location='my_orders.php';</script>";
    exit();
}

// 3. ดึงรายการอาหารในออเดอร์นี้
$sql_details = "SELECT d.*, m.name_menu, m.img_menu 
                FROM tb_order_details d 
                LEFT JOIN tb_menu m ON d.id_menu = m.id_menu 
                WHERE d.order_id = '$order_id'";
$res_details = mysqli_query($conn, $sql_details);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดคำสั่งซื้อ #<?= $order_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kanit', sans-serif; background-color: #fdfaf0; color: #444; }
        .detail-card { border: none; border-radius: 25px; background: #fff; }
        .item-img { width: 60px; height: 60px; object-fit: cover; border-radius: 12px; }
        
        /* Status Tracker Style */
        .status-tracker { display: flex; justify-content: space-between; position: relative; margin-bottom: 30px; }
        .status-step { text-align: center; position: relative; z-index: 2; flex: 1; }
        .step-icon { width: 40px; height: 40px; border-radius: 50%; background: #eee; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; transition: 0.3s; }
        .step-active .step-icon { background: #f57c00; color: white; box-shadow: 0 0 15px rgba(245, 124, 0, 0.4); }
        .line { position: absolute; top: 20px; left: 10%; right: 10%; height: 2px; background: #eee; z-index: 1; }
        
        .slip-preview { border-radius: 20px; cursor: pointer; transition: 0.3s; border: 3px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .text-orange { color: #f57c00; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <div class="d-flex align-items-center mb-4">
                <a href="my_orders.php" class="btn btn-white shadow-sm rounded-circle me-3"><i class="bi bi-chevron-left"></i></a>
                <h3 class="fw-bold mb-0">รายละเอียดคำสั่งซื้อ <span class="text-warning">#<?= $order['order_id'] ?></span></h3>
            </div>

            <div class="card detail-card shadow-sm p-4 mb-4">
                <div class="status-tracker px-2">
                    <div class="line"></div>
                    <div class="status-step step-active">
                        <div class="step-icon"><i class="bi bi-receipt"></i></div>
                        <div class="small">สั่งซื้อแล้ว</div>
                    </div>
                    <?php 
                        $status = $order['order_status'];
                        $is_paid = in_array($status, ['รอตรวจสอบชำระเงิน', 'กำลังทำ', 'สำเร็จ']);
                        $is_cooking = in_array($status, ['กำลังทำ', 'สำเร็จ']);
                        $is_done = ($status == 'สำเร็จ');
                    ?>
                    <div class="status-step <?= $is_paid ? 'step-active' : '' ?>">
                        <div class="step-icon"><i class="bi bi-wallet2"></i></div>
                        <div class="small">จ่ายเงินแล้ว</div>
                    </div>
                    <div class="status-step <?= $is_cooking ? 'step-active' : '' ?>">
                        <div class="step-icon"><i class="bi bi-egg-fried"></i></div>
                        <div class="small">กำลังทำ</div>
                    </div>
                    <div class="status-step <?= $is_done ? 'step-active' : '' ?>">
                        <div class="step-icon"><i class="bi bi-check-lg"></i></div>
                        <div class="small">สำเร็จ</div>
                    </div>
                </div>

                <div class="row g-4 mt-2">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-muted text-uppercase small mb-3 border-bottom pb-2">ข้อมูลผู้รับ</h6>
                        <p class="mb-1 fw-bold"><?= $order['cus_name'] ?></p>
                        <p class="mb-1 text-muted small"><i class="bi bi-telephone me-2"></i><?= $order['cus_tel'] ?></p>
                        <p class="text-muted small"><i class="bi bi-geo-alt me-2"></i><?= $order['cus_address'] ?></p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h6 class="fw-bold text-muted text-uppercase small mb-3 border-bottom pb-2">สรุปการสั่งซื้อ</h6>
                        <h2 class="fw-bold text-orange mb-0">฿<?= number_format($order['total_price'], 2) ?></h2>
                        <p class="small text-muted">สถานะ: <span class="badge bg-warning text-dark"><?= $status ?></span></p>
                    </div>
                </div>

                <h6 class="fw-bold mb-3 mt-4">รายการอาหาร</h6>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle">
                        <thead class="bg-light">
                            <tr class="text-muted small">
                                <th>เมนู</th>
                                <th class="text-center">จำนวน</th>
                                <th class="text-end">ราคาต่อหน่วย</th>
                                <th class="text-end">รวม</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($item = mysqli_fetch_assoc($res_details)): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="img/<?= $item['img_menu'] ?>" class="item-img me-3" onerror="this.src='https://placehold.co/100x100?text=Food'">
                                        <span class="fw-bold"><?= $item['name_menu'] ?></span>
                                    </div>
                                </td>
                                <td class="text-center">x<?= $item['qty'] ?></td>
                                <td class="text-end text-muted">฿<?= number_format($item['price'], 2) ?></td>
                                <td class="text-end fw-bold">฿<?= number_format($item['price'] * $item['qty'], 2) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <h6 class="fw-bold mb-3">หลักฐานการโอนเงิน</h6>
                    <div class="text-center">
                        <?php if($order['slip_img']): ?>
                            <a href="img_slip/<?= $order['slip_img']; ?>" target="_blank">
                                <img src="img_slip/<?= $order['slip_img']; ?>" class="slip-preview img-fluid" style="max-height: 350px;">
                            </a>
                            <p class="mt-2 small text-muted">คลิกที่รูปเพื่อดูหลักฐานขนาดใหญ่</p>
                        <?php else: ?>
                            <div class="py-4 border rounded-4 bg-light">
                                <i class="bi bi-image text-muted display-6"></i>
                                <p class="text-danger mt-2 mb-0">ยังไม่ได้แนบหลักฐานการชำระเงิน</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button onclick="window.print()" class="btn btn-outline-secondary rounded-pill px-4 me-2">
                    <i class="bi bi-printer me-1"></i> พิมพ์ใบเสร็จ
                </button>
                <a href="my_orders.php" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">กลับไปรายการสั่งซื้อ</a>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>