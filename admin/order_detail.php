<?php
session_start();
include '../config.php';

if (!isset($_GET['id'])) {
    header("Location: manage_orders.php");
    exit();
}

$order_id = mysqli_real_escape_string($conn, $_GET['id']);

// 1. ดึงข้อมูลหลักของออเดอร์ (ปรับชื่อคอลัมน์ให้ตรงกับ tb_orders)
$sql_order = "SELECT * FROM tb_orders WHERE order_id = '$order_id'";
$res_order = mysqli_query($conn, $sql_order);
$order = mysqli_fetch_assoc($res_order);

if (!$order) {
    die("ไม่พบข้อมูลออเดอร์ หรือ ชื่อคอลัมน์ในฐานข้อมูลไม่ตรงกัน");
}

// 2. ดึงรายการอาหารในออเดอร์นี้ (เชื่อม table tb_order_details กับ tb_menu)
$sql_items = "SELECT d.*, m.name_menu FROM tb_order_details d 
              JOIN tb_menu m ON d.id_menu = m.id_menu 
              WHERE d.order_id = '$order_id'";
$res_items = mysqli_query($conn, $sql_items);
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายละเอียดออเดอร์ #<?= $order_id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Kanit', sans-serif; }
        .detail-card { border-radius: 20px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="mb-4">
        <a href="manage_orders.php" class="btn btn-outline-dark rounded-pill px-4">← กลับหน้าจัดการ</a>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card detail-card p-4 h-100">
                <h5 class="fw-bold mb-3 text-success">ข้อมูลลูกค้า</h5>
                <p class="mb-1 text-muted small">ชื่อผู้สั่ง:</p>
                <p class="fw-bold"><?= htmlspecialchars($order['cus_name']) ?></p> <p class="mb-1 text-muted small">เบอร์โทรศัพท์:</p>
                <p class="fw-bold"><?= htmlspecialchars($order['cus_tel']) ?></p> <p class="mb-1 text-muted small">ที่อยู่จัดส่ง:</p>
                <p><?= nl2br(htmlspecialchars($order['cus_address'])) ?></p>
                
                <hr>
                <p class="mb-1 text-muted small">สถานะปัจจุบัน:</p>
                <span class="badge bg-info text-dark rounded-pill px-3"><?= $order['order_status'] ?></span> </div>
        </div>

        <div class="col-md-8">
            <div class="card detail-card p-4">
                <h5 class="fw-bold mb-4">รายการสินค้าที่สั่ง</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>เมนู</th>
                                <th class="text-center">ราคา</th>
                                <th class="text-center">จำนวน</th>
                                <th class="text-end">รวม</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($item = mysqli_fetch_assoc($res_items)): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name_menu']) ?></td>
                                <td class="text-center">฿<?= number_format($item['price'], 0) ?></td>
                                <td class="text-center"><?= $item['qty'] ?></td>
                                <td class="text-end fw-bold">฿<?= number_format($item['price'] * $item['qty'], 0) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="3" class="text-end fw-bold">ยอดรวมสุทธิ</td>
                                <td class="text-end fw-bold text-success fs-4">฿<?= number_format($order['total_price'], 0) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>