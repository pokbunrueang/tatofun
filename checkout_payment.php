<?php
session_start();
include 'config.php';

// 1. ดึงเลข order_id จาก URL
$order_id = isset($_GET['order_id']) ? mysqli_real_escape_string($conn, $_GET['order_id']) : 0;

// 2. ดึงข้อมูลออเดอร์
$sql = "SELECT * FROM tb_orders WHERE order_id = '$order_id'";
$result = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    echo "<script>alert('ไม่พบข้อมูลการสั่งซื้อ'); window.location='index.php';</script>";
    exit();
}

$total_price = $order['total_price'];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ชำระเงิน - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kanit', sans-serif; background-color: #fffbf0; }
        .payment-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .text-orange { color: #f57c00; }
        .qr-frame { background: white; padding: 20px; border-radius: 15px; border: 2px dashed #ffc107; display: inline-block; }
        .btn-warning { background-color: #ffca28; border: none; transition: 0.3s; }
        .btn-warning:hover { background-color: #f57c00; color: white; transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card payment-card p-4">
                <h3 class="fw-bold text-center mb-4">ชำระเงิน</h3>
                
                <div class="text-center mb-4">
                    <p class="mb-1 text-muted">คำสั่งซื้อเลขที่: #<?= $order['order_id'] ?></p>
                    <h2 class="fw-bold text-orange">฿<?= number_format($total_price, 2); ?></h2>
                    <p class="small text-muted">ยอดชำระสุทธิ</p>
                </div>

                <div class="text-center mb-4">
                    <div class="qr-frame">
                        <img src="https://promptpay.io/0800000000/<?= $total_price ?>.png" 
                             class="img-fluid rounded shadow-sm" style="max-width: 250px;">
                    </div>
                    <p class="mt-3 mb-0 fw-bold">บริษัท ทาโทฟัน จำกัด</p>
                    <p class="small text-muted">สแกนจ่ายผ่านแอปธนาคารได้ทุกธนาคาร</p>
                </div>

                <form action="payment_db.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="order_id" value="<?= $order_id; ?>">
                    
                    <div class="mb-4 text-start">
                        <label class="form-label fw-bold small"><i class="bi bi-image me-1"></i>แนบสลิปการโอนเงิน</label>
                        <input type="file" name="slip" class="form-control" required accept="image/*">
                        <div class="form-text">รองรับไฟล์รูปภาพ JPG, PNG</div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold py-3 shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> ยืนยันการแจ้งชำระเงิน
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="index.php" class="text-muted small text-decoration-none">กลับหน้าหลัก</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>