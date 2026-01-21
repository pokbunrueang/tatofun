<?php
session_start();
include '../config.php';
// ตรวจสอบสิทธิ์ Admin (ตามที่คุณตั้งค่าไว้)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("Location: ../login.php"); exit(); }

$sql = "SELECT p.*, o.total_price, o.order_status, u.username 
        FROM tb_payment p
        JOIN tb_order o ON p.order_id = o.order_id
        JOIN tb_user u ON o.user_id = u.id 
        WHERE o.order_status = 'waiting_verify'";
$result = mysqli_query($conn, $sql);
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>จัดการการชำระเงิน - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .slip-img { width: 100px; cursor: pointer; transition: 0.3s; border-radius: 5px; }
        .slip-img:hover { transform: scale(1.1); }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-check2-circle text-success"></i> ตรวจสอบการชำระเงิน</h2>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>ลูกค้า</th>
                        <th>ยอดที่ต้องจ่าย</th>
                        <th>ยอดที่แจ้งโอน</th>
                        <th>หลักฐาน</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>#<?= $row['order_id'] ?></td>
                        <td><?= $row['username'] ?></td>
                        <td><?= number_format($row['total_price'], 2) ?> ฿</td>
                        <td class="text-primary fw-bold"><?= number_format($row['pay_amount'], 2) ?> ฿</td>
                        <td>
                            <a href="img_slip/<?= $row['pay_slip'] ?>" target="_blank">
                                <img src="img_slip/<?= $row['pay_slip'] ?>" class="slip-img shadow-sm">
                            </a>
                        </td>
                        <td>
                            <a href="approve_payment.php?id=<?= $row['order_id'] ?>&status=approve" 
                               class="btn btn-success btn-sm" onclick="return confirm('ยืนยันยอดเงินถูกต้อง?')">
                               <i class="bi bi-check-lg"></i> อนุมัติ
                            </a>
                            <a href="approve_payment.php?id=<?= $row['order_id'] ?>&status=reject" 
                               class="btn btn-danger btn-sm" onclick="return confirm('ปฏิเสธรายการนี้?')">
                               <i class="bi bi-x-lg"></i> ปฏิเสธ
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if(mysqli_num_rows($result) == 0): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">ไม่มีรายการรอตรวจสอบ</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <a href="index_ad.php" class="btn btn-secondary mt-3">กลับหน้า Dashboard</a>
</div>

</body>
</html>