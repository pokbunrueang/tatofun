<?php
session_start();
include '../config.php';

if (!isset($_GET['id'])) { header("Location: manage_orders.php"); exit(); }
$order_id = mysqli_real_escape_string($conn, $_GET['id']);

// ดึงข้อมูลออเดอร์และรายละเอียดสินค้า (JOIN ตาราง)
$sql = "SELECT o.*, d.*, m.menu_name 
        FROM tb_orders o
        JOIN tb_order_details d ON o.order_id = d.order_id
        JOIN tb_menus m ON d.menu_id = m.menu_id
        WHERE o.order_id = '$order_id'";
$result = mysqli_query($conn, $sql);
$order_info = mysqli_fetch_assoc($result);
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายละเอียดออเดอร์ #<?= $order_id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fffdf0; font-family: 'Kanit', sans-serif; }
        .detail-card { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="card detail-card p-4">
        <h3 class="fw-bold text-success mb-4">รายละเอียดใบสั่งซื้อ #<?= $order_id ?></h3>
        <p><strong>ชื่อลูกค้า:</strong> <?= $order_info['cust_name'] ?></p>
        <p><strong>เบอร์โทรศัพท์:</strong> <?= $order_info['cust_phone'] ?></p>
        <hr>
        <table class="table">
            <thead>
                <tr>
                    <th>รายการอาหาร</th>
                    <th class="text-center">จำนวน</th>
                    <th class="text-end">ราคา/หน่วย</th>
                    <th class="text-end">รวม</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                mysqli_data_seek($result, 0); // รีเซ็ต pointer เพื่อวนลูปใหม่
                while($item = mysqli_fetch_assoc($result)): 
                ?>
                <tr>
                    <td><?= $item['menu_name'] ?></td>
                    <td class="text-center"><?= $item['qty'] ?></td>
                    <td class="text-end"><?= number_format($item['price'], 2) ?></td>
                    <td class="text-end"><?= number_format($item['qty'] * $item['price'], 2) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr class="fw-bold fs-5">
                    <td colspan="3" class="text-end">ยอดรวมทั้งสิ้น:</td>
                    <td class="text-end text-primary"><?= number_format($order_info['total_price'], 2) ?> บาท</td>
                </tr>
            </tfoot>
        </table>
        <a href="manage_orders.php" class="btn btn-secondary rounded-pill mt-3">กลับหน้าจัดการออเดอร์</a>
    </div>
</div>
</body>
</html>