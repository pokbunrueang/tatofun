<?php
session_start();
include '../config.php'; 

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// 1. ดึงรายการออเดอร์ทั้งหมด
$sql = "SELECT * FROM tb_orders ORDER BY order_date DESC";
$result = mysqli_query($conn, $sql);

// 2. นับจำนวนออเดอร์ที่ 'รอตรวจสอบ' (Pending) เพื่อแสดงใน Card ด้านล่าง
$sql_pending = "SELECT COUNT(*) as total FROM tb_orders WHERE status = 'Pending'";
$res_pending = mysqli_query($conn, $sql_pending);
$row_pending = mysqli_fetch_assoc($res_pending);
$pending_count = $row_pending['total'];
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการรายการสั่งซื้อ - TatoFun Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');
        body { background-color: #fffdf0; font-family: 'Kanit', sans-serif; }
        .order-card { border-radius: 25px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: #ffffff; }
        .table thead th { background-color: #f8f9fa; color: #666; font-weight: 600; padding: 15px; border: none; }
        .table tbody td { vertical-align: middle; padding: 15px; border-bottom: 1px solid #f8f9fa; }
        .status-badge { border-radius: 50px; padding: 5px 15px; font-weight: 600; font-size: 0.85rem; display: inline-block; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-shipping { background: #cfe2ff; color: #084298; }
        .status-success { background: #d1e7dd; color: #0f5132; }
        .btn-view { border-radius: 12px; transition: 0.3s; font-weight: 500; }
        .icon-circle { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-4 shadow-sm border-start border-success border-5">
        <h4 class="fw-bold text-success mb-0">
            <i class="bi bi-clipboard-check-fill me-2"></i> รายการสั่งซื้อลูกค้า
        </h4>
        <a href="index_ad.php" class="btn btn-secondary btn-sm rounded-pill px-4 shadow-sm">
            ← กลับหน้าหลัก Admin
        </a>
    </div>

    <div class="card order-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">เลขที่ออเดอร์</th>
                        <th>วันที่สั่งซื้อ</th>
                        <th>ชื่อลูกค้า</th>
                        <th class="text-end">ยอดรวม</th>
                        <th class="text-center">สถานะ</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="text-center fw-bold text-muted">#<?= $row['order_id'] ?></td>
                        <td><small><?= date('d/m/Y H:i', strtotime($row['order_date'])) ?></small></td>
                        <td>
                            <div class="fw-bold"><?= htmlspecialchars($row['cust_name']) ?></div>
                            <small class="text-muted"><i class="bi bi-telephone me-1"></i><?= $row['cust_phone'] ?></small>
                        </td>
                        <td class="text-end fw-bold text-primary"><?= number_format($row['total_price'], 2) ?> ฿</td>
                        <td class="text-center">
                            <?php
                                $s = $row['status'];
                                $c = ($s == 'Pending') ? 'status-pending' : (($s == 'Shipping') ? 'status-shipping' : 'status-success');
                                $t = ($s == 'Pending') ? 'รอดำเนินการ' : (($s == 'Shipping') ? 'กำลังส่ง' : 'สำเร็จแล้ว');
                            ?>
                            <span class="status-badge <?= $c ?>"><?= $t ?></span>
                        </td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm btn-view border shadow-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    จัดการ
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 15px;">
                                    <li><a class="dropdown-item py-2" href="order_detail.php?id=<?= $row['order_id'] ?>"><i class="bi bi-eye me-2 text-primary"></i>รายละเอียด</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item py-2 text-primary" href="update_status.php?id=<?= $row['order_id'] ?>&status=Shipping"><i class="bi bi-truck me-2"></i>กำลังส่งสินค้า</a></li>
                                    <li><a class="dropdown-item py-2 text-success" href="update_status.php?id=<?= $row['order_id'] ?>&status=Success"><i class="bi bi-check-circle me-2"></i>จัดส่งสำเร็จ</a></li>
                                    <li><a class="dropdown-item py-2 text-danger" href="update_status.php?id=<?= $row['order_id'] ?>&status=Cancel" onclick="return confirm('ยืนยันการยกเลิก?')"><i class="bi bi-x-circle me-2"></i>ยกเลิกออเดอร์</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-0 rounded-4 shadow-sm p-3 bg-white border-bottom border-warning border-4">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-warning bg-opacity-10 text-warning me-3 rounded-circle">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <div>
                        <div class="small text-muted">รอตรวจสอบ</div>
                        <h4 class="fw-bold mb-0"><?= $pending_count ?> รายการ</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ตรวจสอบ URL Parameter เพื่อแสดงการแจ้งเตือน
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('msg') === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'บันทึกสำเร็จ!',
            text: 'สถานะออเดอร์ถูกอัปเดตแล้ว',
            showConfirmButton: false,
            timer: 1500,
            customClass: { popup: 'rounded-4' }
        });
    }
</script>
</body>
</html>