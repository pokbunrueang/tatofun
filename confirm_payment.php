<?php
session_start();
include 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

// ดึงรายการออเดอร์ที่ยังไม่ได้ชำระเงินของลูกค้าคนนี้
$u_id = $_SESSION['user_id'];
$sql_orders = "SELECT order_id, total_price FROM tb_order WHERE user_id = '$u_id' AND order_status = 'pending'";
$res_orders = mysqli_query($conn, $sql_orders);
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แจ้งชำระเงิน - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #fffbf0; font-family: 'Kanit', sans-serif; }
        .card-pay { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .btn-confirm { background-color: #f57c00; color: white; border-radius: 25px; font-weight: bold; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-pay p-4">
                <h3 class="text-center fw-bold mb-4"><i class="bi bi-wallet2 text-warning"></i> แจ้งชำระเงิน</h3>
                
                <form action="save_payment.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">เลือกรายการสั่งซื้อ</label>
                        <select name="order_id" class="form-select" required>
                            <option value="">-- เลือกออเดอร์ของคุณ --</option>
                            <?php while($row = mysqli_fetch_assoc($res_orders)): ?>
                                <option value="<?= $row['order_id'] ?>">Order #<?= $row['order_id'] ?> (ยอด <?= $row['total_price'] ?> ฿)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">จำนวนเงินที่โอน</label>
                            <input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">วันเวลาที่โอน</label>
                            <input type="datetime-local" name="pay_date" class="form-control" required>
                        </div>
                    </div>

                    <hr>
                    <h5 class="fw-bold mb-3"><i class="bi bi-truck text-warning"></i> ข้อมูลจัดส่ง</h5>
                    <div class="mb-3">
                        <input type="text" name="ship_name" class="form-control mb-2" placeholder="ชื่อผู้รับ" required>
                        <input type="text" name="ship_phone" class="form-control mb-2" placeholder="เบอร์โทรศัพท์" required>
                        <textarea name="ship_address" class="form-control" rows="3" placeholder="ที่อยู่จัดส่งโดยละเอียด" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">แนบหลักฐานการโอน (สลิป)</label>
                        <input type="file" name="slip" class="form-control" accept="image/*" required>
                    </div>

                    <button type="submit" class="btn btn-confirm w-100 py-2">ยืนยันการแจ้งชำระเงิน</button>
                    <a href="index.php" class="btn btn-light w-100 mt-2 rounded-pill">กลับหน้าแรก</a>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>