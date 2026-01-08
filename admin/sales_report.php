<?php
session_start();
include '../config.php';

// ดึงยอดขายรวมที่สำเร็จแล้ว
$sql = "SELECT SUM(total_price) as grand_total FROM tb_orders WHERE status = 'Success'";
$res = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($res);
$total_sales = $data['grand_total'] ?? 0;
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายงานยอดขาย - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');
        body { background-color: #fffdf0; font-family: 'Kanit', sans-serif; }
        .report-card { border-radius: 30px; border: none; background: white; box-shadow: 0 15px 35px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card report-card p-5">
                <div class="mb-4">
                    <i class="bi bi-graph-up-arrow text-success" style="font-size: 4rem;"></i>
                </div>
                <h2 class="fw-bold mb-2">สรุปรายงานยอดขายสะสม</h2>
                <p class="text-muted mb-4">ข้อมูลล่าสุดจากระบบจัดการร้าน TatoFun</p>
                
                <div class="bg-light p-4 rounded-4 mb-4">
                    <small class="text-uppercase fw-bold text-muted">ยอดขายรวมทั้งหมด</small>
                    <h1 class="display-3 fw-bold text-success mb-0">
                        <?= number_format($total_sales, 2) ?> <small class="fs-4 text-muted">บาท</small>
                    </h1>
                </div>

                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <a href="index_ad.php" class="btn btn-dark rounded-pill px-4">กลับหน้าหลัก Admin</a>
                    <button onclick="window.print()" class="btn btn-outline-primary rounded-pill px-4">พิมพ์รายงาน</button>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>