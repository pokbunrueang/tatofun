<?php
session_start();
include '../config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { exit(); }

$order_id = $_GET['id'];

// ดึงข้อมูลที่อยู่จาก tb_shipping
$sql = "SELECT * FROM tb_shipping WHERE order_id = '$order_id'";
$result = mysqli_query($conn, $sql);
$ship = mysqli_fetch_assoc($result);

if (!$ship) { echo "ไม่พบข้อมูลที่อยู่จัดส่ง"; exit(); }
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Print Label - Order #<?= $order_id ?></title>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
        body { font-family: 'Tahoma', sans-serif; padding: 20px; }
        .label-box {
            width: 10cm;
            height: 15cm;
            border: 2px solid #000;
            padding: 20px;
            margin: 0 auto;
            position: relative;
        }
        .sender { border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 20px; }
        .receiver { margin-top: 50px; padding: 10px; border: 1px solid #ccc; background: #f9f9f9; }
        .order-ref { position: absolute; bottom: 10px; right: 10px; font-size: 12px; }
        .btn-print { 
            background: #28a745; color: #fff; padding: 10px 20px; 
            border: none; border-radius: 5px; cursor: pointer; margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="no-print" style="text-align: center;">
    <button class="btn-print" onclick="window.print();">คลิกเพื่อพิมพ์ใบปะหน้า</button>
</div>

<div class="label-box">
    <div class="sender">
        <strong>ผู้ส่ง (SENDER)</strong><br>
        ร้าน TatoFun (Fresh & Fun Fries)<br>
        123 ถ.สเตชั่น แขวง/เขต กรุงเทพฯ 10xxx<br>
        โทร: 081-XXX-XXXX
    </div>

    <div class="receiver">
        <strong style="font-size: 1.2em;">ผู้รับ (RECEIVER)</strong><br><br>
        <span style="font-size: 1.4em; font-weight: bold;"><?= $ship['ship_name'] ?></span><br>
        <span style="font-size: 1.2em;">
            <?= nl2br($ship['ship_address']) ?>
        </span><br><br>
        <strong>โทร: <?= $ship['ship_phone'] ?></strong>
    </div>

    <div class="order-ref">
        Order ID: #<?= $order_id ?> | พิมพ์เมื่อ: <?= date('d/m/Y H:i') ?>
    </div>
</div>

</body>
</html>