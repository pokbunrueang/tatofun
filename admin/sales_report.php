<?php
session_start();
include '../config.php';

// ดึงยอดขายรวมทั้งหมด
$sql_total = "SELECT SUM(total_price) as grand_total FROM tb_orders WHERE status = 'Success'";
$res_total = mysqli_query($conn, $sql_total);
$row_total = mysqli_fetch_assoc($res_total);
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายงานยอดขาย - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #fffdf0;">
<div class="container py-5 text-center">
    <div class="card border-0 shadow-sm p-5 rounded-4">
        <h2 class="fw-bold mb-4">💰 รายงานยอดขายสะสม</h2>
        <h1 class="display-3 fw-bold text-success">
            <?= number_format($row_total['grand_total'], 2) ?> <small class="fs-4 text-muted">บาท</small>
        </h1>
        <p class="text-muted mt-3">นับเฉพาะรายการที่สถานะเป็น "สำเร็จแล้ว" เท่านั้น</p>
        <div class="mt-4">
            <a href="index_ad.php" class="btn btn-outline-secondary rounded-pill px-4">กลับหน้าหลัก Admin</a>
        </div>
    </div>
</div>
</body>
</html>