<?php
session_start();
include 'config.php';

// 1. ดึงข้อมูลสมาชิก (ถ้ามีการ Login ไว้)
$user_name = ""; 
$user_phone = ""; 
$user_address = "";

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // คำสั่งดึงข้อมูลสมาชิกมาแสดงในช่องกรอกอัตโนมัติ
    $sql_user = "SELECT * FROM tb_users WHERE user_id = '$user_id'"; 
    $res_user = mysqli_query($conn, $sql_user);

    // ตรวจสอบว่า query สำเร็จและเจอข้อมูลไหม เพื่อไม่ให้หน้าเว็บค้าง (Fatal Error)
    if ($res_user && mysqli_num_rows($res_user) > 0) {
        $user_data = mysqli_fetch_assoc($res_user);
        $user_name = $user_data['fullname'] ?? "";
        $user_phone = $user_data['phone'] ?? "";
        // หากในฐานข้อมูลไม่มีคอลัมน์ address จะเป็นค่าว่างไว้ให้กรอกเอง
        $user_address = $user_data['address'] ?? ""; 
    }
}

// 2. ตรวจสอบตะกร้าสินค้า ถ้าไม่มีของให้เด้งกลับไปหน้าเมนู
$cart_items = $_SESSION['cart'] ?? [];
if (empty($cart_items)) {
    echo "<script>alert('ยังไม่มีสินค้าในตะกร้าครับ'); window.location='menu.php';</script>";
    exit();
}

$total_price = 0;
$total_qty = 0;
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ยืนยันคำสั่งซื้อ - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Kanit', sans-serif; }
        .checkout-card { border: none; border-radius: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .form-control { border-radius: 10px; padding: 12px; border: 1px solid #eee; }
        .btn-confirm { background-color: #28a745; color: white; border-radius: 12px; padding: 15px; font-weight: 600; border: none; width: 100%; transition: 0.3s; }
        .btn-confirm:hover { background-color: #218838; transform: translateY(-2px); }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="btn btn-outline-dark rounded-pill px-3 py-1" href="cart.php"><i class="bi bi-chevron-left"></i> กลับไปตะกร้า</a>
        <span class="fw-bold">ข้อมูลการจัดส่ง</span>
    </div>
</nav>

<div class="container py-5">
    <form action="save_order.php" method="POST">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card checkout-card p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-person-fill me-2 text-warning"></i>ที่อยู่สำหรับจัดส่ง</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">ชื่อ-นามสกุล</label>
                            <input type="text" name="cus_name" class="form-control" value="<?= htmlspecialchars($user_name) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">เบอร์โทรศัพท์</label>
                            <input type="tel" name="cus_tel" class="form-control" value="<?= htmlspecialchars($user_phone) ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted">ที่อยู่จัดส่ง / เลขโต๊ะ</label>
                            <textarea name="cus_address" class="form-control" rows="3" required><?= htmlspecialchars($user_address) ?></textarea>
                        </div>
                    </div>
                    
                    <h5 class="fw-bold mt-5 mb-4"><i class="bi bi-credit-card-fill me-2 text-warning"></i>วิธีชำระเงิน</h5>
                    <div class="form-check p-3 border rounded-3 mb-2">
                        <input class="form-check-input ms-0 me-3" type="radio" name="payment" id="pay1" value="โอนเงิน" checked>
                        <label class="form-check-label fw-bold" for="pay1">โอนเงินผ่าน PromptPay</label>
                    </div>
                    <div class="form-check p-3 border rounded-3">
                        <input class="form-check-input ms-0 me-3" type="radio" name="payment" id="pay2" value="เงินสด">
                        <label class="form-check-label fw-bold" for="pay2">เงินสด / ชำระที่เคาน์เตอร์</label>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card checkout-card p-4">
                    <h5 class="fw-bold mb-4">สรุปคำสั่งซื้อ</h5>
                    <div class="list-group list-group-flush mb-4">
                        <?php 
                        foreach ($cart_items as $id => $qty): 
                            $id_safe = mysqli_real_escape_string($conn, $id);
                            $sql_m = "SELECT * FROM tb_menu WHERE id_menu = '$id_safe'";
                            $res_m = mysqli_query($conn, $sql_m);
                            if ($row = mysqli_fetch_assoc($res_m)):
                                $subtotal = $row['price_menu'] * $qty;
                                $total_price += $subtotal;
                                $total_qty += $qty;
                        ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <span class="fw-bold"><?= htmlspecialchars($row['name_menu']) ?></span>
                                <div class="small text-muted">จำนวน <?= $qty ?> ชิ้น</div>
                            </div>
                            <span class="fw-bold">฿<?= number_format($subtotal, 0) ?></span>
                        </div>
                        <?php endif; endforeach; ?>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">ยอดรวมสินค้า (<?= $total_qty ?> ชิ้น)</span>
                        <span class="fw-bold">฿<?= number_format($total_price, 0) ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-4">ยอดสุทธิ</span>
                        <span class="fw-bold fs-4 text-warning">฿<?= number_format($total_price, 0) ?></span>
                    </div>

                    <button type="submit" class="btn btn-confirm shadow-sm">ยืนยันคำสั่งซื้อ</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>