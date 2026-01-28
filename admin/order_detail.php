<?php
session_start();
include '../config.php';

if (!isset($_GET['id'])) {
    header("Location: manage_orders.php");
    exit();
}

// ตรวจสอบสิทธิ์ Admin (เพิ่มเพื่อความปลอดภัย)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$order_id = mysqli_real_escape_string($conn, $_GET['id']);

// 1. ดึงข้อมูลหลักของออเดอร์
$sql_order = "SELECT * FROM tb_orders WHERE order_id = '$order_id'";
$res_order = mysqli_query($conn, $sql_order);
$order = mysqli_fetch_assoc($res_order);

if (!$order) {
    die("<div class='container mt-5 alert alert-danger text-center rounded-4'>ไม่พบข้อมูลออเดอร์ในระบบ</div>");
}

// 2. ดึงรายการอาหาร
$sql_items = "SELECT d.*, m.name_menu FROM tb_order_details d 
              JOIN tb_menu m ON d.id_menu = m.id_menu 
              WHERE d.order_id = '$order_id'";
$res_items = mysqli_query($conn, $sql_items);
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>รายละเอียดออเดอร์ #<?= $order_id ?> - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fffdf0; font-family: 'Kanit', sans-serif; }
        .detail-card { border-radius: 25px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: #ffffff; }
        .status-badge { border-radius: 50px; padding: 8px 20px; font-weight: 600; }
        
        /* ปุ่มย้อนกลับมาตรฐาน */
        .btn-back-standard {
            width: 140px;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .item-row:hover { background-color: #fdfdfd; }
        .label-text { color: #888; font-size: 0.85rem; font-weight: 400; }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-4 shadow-sm border-start border-primary border-5">
        <h4 class="fw-bold text-dark mb-0">
            <i class="bi bi-receipt me-2 text-primary"></i> รายละเอียดออเดอร์ <span class="text-primary">#<?= $order_id ?></span>
        </h4>
        <a href="manage_orders.php" class="btn btn-secondary btn-sm btn-back-standard d-flex align-items-center justify-content-center">
            <i class="bi bi-arrow-left-circle me-2"></i> กลับหน้าจัดการ
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card detail-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                        <i class="bi bi-person-badge fs-4 text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-0">ข้อมูลลูกค้า</h5>
                </div>

                <div class="mb-3">
                    <div class="label-text">ชื่อผู้สั่ง</div>
                    <div class="fw-bold fs-5 text-dark"><?= htmlspecialchars($order['cus_name']) ?></div>
                </div>

                <div class="mb-3">
                    <div class="label-text">เบอร์โทรศัพท์</div>
                    <div class="fw-bold text-dark"><i class="bi bi-telephone me-2 text-muted"></i><?= htmlspecialchars($order['cus_tel']) ?></div>
                </div>

                <div class="mb-4">
                    <div class="label-text">ที่อยู่จัดส่ง</div>
                    <div class="bg-light p-3 rounded-3 mt-1 shadow-sm" style="font-size: 0.95rem;">
                        <?= nl2br(htmlspecialchars($order['cus_address'])) ?>
                    </div>
                </div>

                <hr class="opacity-50">

                <div class="mb-2">
                    <div class="label-text mb-2">สถานะปัจจุบัน</div>
                    <?php 
                        $s = $order['order_status'];
                        $badge_class = "bg-warning text-dark";
                        if($s == 'กำลังส่ง') $badge_class = "bg-info text-white";
                        if($s == 'สำเร็จแล้ว') $badge_class = "bg-success text-white";
                    ?>
                    <div class="status-badge <?= $badge_class ?> text-center shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> <?= $s ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card detail-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-success bg-opacity-10 p-2 rounded-3 me-3">
                        <i class="bi bi-basket3 fs-4 text-success"></i>
                    </div>
                    <h5 class="fw-bold mb-0">รายการอาหารที่สั่ง</h5>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th class="border-0 rounded-start">เมนู</th>
                                <th class="border-0 text-center">ราคา/ชิ้น</th>
                                <th class="border-0 text-center">จำนวน</th>
                                <th class="border-0 text-end rounded-end">รวม</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($item = mysqli_fetch_assoc($res_items)): ?>
                            <tr class="item-row">
                                <td class="fw-bold py-3 text-dark"><?= htmlspecialchars($item['name_menu']) ?></td>
                                <td class="text-center text-muted">฿<?= number_format($item['price'], 2) ?></td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-3"><?= $item['qty'] ?></span>
                                </td>
                                <td class="text-end fw-bold">฿<?= number_format($item['price'] * $item['qty'], 2) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold py-4 fs-5 border-0">ยอดรวมสุทธิ:</td>
                                <td class="text-end fw-bold text-success fs-3 py-4 border-0">
                                    <span class="fs-6 text-muted fw-normal">฿</span> <?= number_format($order['total_price'], 2) ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-3">
                    <a href="receipt_print.php?id=<?= $order_id ?>" target="_blank" class="btn btn-outline-info rounded-pill px-4 btn-sm shadow-sm">
                        <i class="bi bi-printer me-1"></i> พิมพ์ใบเสร็จ
                    </a>
                    <a href="print_shipping.php?id=<?= $order_id ?>" target="_blank" class="btn btn-outline-dark rounded-pill px-4 btn-sm shadow-sm">
                        <i class="bi bi-box-seam me-1"></i> พิมพ์ที่อยู่จัดส่ง
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>