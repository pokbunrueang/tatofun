<?php
session_start();
include '../config.php';

if (!isset($_GET['id'])) { header("Location: manage_orders.php"); exit(); }
$order_id = mysqli_real_escape_string($conn, $_GET['id']);

// ปรับ SQL ให้ตรงกับชื่อตารางของคุณ
$sql = "SELECT o.*, d.*, m.menu_name 
        FROM tb_orders o
        JOIN tb_order_details d ON o.order_id = d.order_id
        JOIN tb_menu m ON d.menu_id = m.menu_id
        WHERE o.order_id = '$order_id'";

$result = mysqli_query($conn, $sql);

// ป้องกัน Fatal Error หาก Query ผิดพลาด
if (!$result || mysqli_num_rows($result) == 0) {
    die("<div class='container py-5'><div class='alert alert-danger'>ไม่พบข้อมูลออเดอร์ หรือ ชื่อคอลัมน์ในฐานข้อมูลไม่ตรงกัน</div></div>");
}

$order_info = mysqli_fetch_assoc($result);
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายละเอียดออเดอร์ #<?= $order_id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');
        body { background-color: #fffdf0; font-family: 'Kanit', sans-serif; }
        .detail-card { border-radius: 25px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: #ffffff; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="card detail-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-success mb-0">📦 รายละเอียดออเดอร์ #<?= $order_id ?></h3>
            <span class="badge bg-primary rounded-pill px-3">สถานะ: <?= $order_info['status'] ?></span>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <p class="mb-1 text-muted">ข้อมูลลูกค้า</p>
                <h5 class="fw-bold"><?= htmlspecialchars($order_info['name'] ?? 'ไม่ระบุชื่อ') ?></h5>
                <p class="mb-0"><i class="bi bi-telephone"></i> <?= $order_info['phone'] ?? '-' ?></p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>รายการอาหาร</th>
                        <th class="text-center">จำนวน</th>
                        <th class="text-end">ราคา/หน่วย</th>
                        <th class="text-end">รวม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    mysqli_data_seek($result, 0); 
                    while($item = mysqli_fetch_assoc($result)): 
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($item['menu_name']) ?></td>
                        <td class="text-center"><?= $item['qty'] ?></td>
                        <td class="text-end"><?= number_format($item['price'], 2) ?></td>
                        <td class="text-end fw-bold"><?= number_format($item['qty'] * $item['price'], 2) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr class="fs-5">
                        <td colspan="3" class="text-end fw-bold">ยอดรวมสุทธิ:</td>
                        <td class="text-end text-primary fw-bold"><?= number_format($order_info['total_price'], 2) ?> บาท</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="text-center mt-4">
            <a href="manage_orders.php" class="btn btn-secondary rounded-pill px-5">กลับหน้าจัดการ</a>
        </div>
    </div>
</div>
</body>
</html>