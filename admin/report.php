<?php
session_start();
include '../config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { exit(); }

// 1. สรุปยอดรวมทั้งหมดจากตาราง finance
$sql_total = "SELECT SUM(amount) as grand_total, COUNT(id_finance) as total_orders FROM tb_finance";
$res_total = mysqli_query($conn, $sql_total);
$data_total = mysqli_fetch_assoc($res_total);

// 2. สรุปยอดแยกตามวัน (7 วันล่าสุด)
$sql_daily = "SELECT DATE(date_added) as sale_date, SUM(amount) as daily_amount 
              FROM tb_finance 
              GROUP BY DATE(date_added) 
              ORDER BY sale_date DESC LIMIT 7";
$res_daily = mysqli_query($conn, $sql_daily);

// 3. จัดอันดับเมนูขายดี (Join กับ tb_menu และ tb_order_detail)
$sql_best = "SELECT m.name_menu, SUM(d.qty) as total_qty 
             FROM tb_order_detail d
             JOIN tb_menu m ON d.menu_id = m.id_menu
             GROUP BY d.menu_id 
             ORDER BY total_qty DESC LIMIT 5";
$res_best = mysqli_query($conn, $sql_best);
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายงานการขาย - TatoFun Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Kanit', sans-serif; }
        .stat-card { border: none; border-radius: 15px; transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-graph-up-arrow text-success"></i> รายงานสรุปยอดขาย</h2>
        <a href="index_ad.php" class="btn btn-secondary rounded-pill shadow-sm">กลับหน้าหลัก</a>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card stat-card bg-primary text-white p-4 shadow-sm">
                <h5>ยอดขายรวมทั้งหมด</h5>
                <h1 class="fw-bold"><?= number_format($data_total['grand_total'], 2) ?> ฿</h1>
                <small>จากทั้งหมด <?= $data_total['total_orders'] ?> รายการสั่งซื้อ</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card bg-success text-white p-4 shadow-sm">
                <h5>ออเดอร์วันนี้</h5>
                <?php 
                    $today = date('Y-m-d');
                    $sql_today = "SELECT SUM(amount) as today_sum FROM tb_finance WHERE DATE(date_added) = '$today'";
                    $res_today = mysqli_fetch_assoc(mysqli_query($conn, $sql_today));
                ?>
                <h1 class="fw-bold"><?= number_format($res_today['today_sum'] ?? 0, 2) ?> ฿</h1>
                <small>ประจำวันที่ <?= date('d/m/Y') ?></small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">ยอดขาย 7 วันล่าสุด</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>วันที่</th>
                                <th class="text-end">ยอดเงิน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($day = mysqli_fetch_assoc($res_daily)): ?>
                            <tr>
                                <td><?= date('d M Y', strtotime($day['sale_date'])) ?></td>
                                <td class="text-end fw-bold text-primary"><?= number_format($day['daily_amount'], 2) ?> ฿</td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">5 อันดับเมนูขายดี</h5>
                    <?php $rank = 1; while($best = mysqli_fetch_assoc($res_best)): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span><?= $rank++ ?>. <?= $best['name_menu'] ?></span>
                            <span class="badge bg-warning text-dark rounded-pill"><?= $best['total_qty'] ?> ชุด</span>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>