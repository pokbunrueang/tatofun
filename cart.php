<?php
session_start();
include 'config.php'; 

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_price = 0;
$total_qty = 0;

// คำนวณจำนวนชิ้นทั้งหมด (เพื่อให้ขึ้น 13 หรือ 16 ชิ้น)
if (!empty($cart_items)) {
    foreach ($cart_items as $qty) {
        $total_qty += $qty;
    }
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ตะกร้าของฉัน - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fdfdfd; font-family: 'Kanit', sans-serif; }
        .navbar { background-color: #fff !important; padding: 15px 0; }
        .cart-card { border: none; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .product-img { width: 80px; height: 80px; object-fit: cover; border-radius: 15px; }
        .btn-checkout { background-color: #ffc107; color: #000; font-weight: 600; border-radius: 12px; border: none; padding: 12px; transition: 0.3s; }
        .btn-checkout:hover:not([disabled]) { background-color: #ff9800; transform: translateY(-2px); }
        .cart-badge { background-color: #ffc107; color: #000; font-weight: bold; }
        .table thead th { border-top: none; }
    </style>
</head>
<body>

<nav class="navbar navbar-light sticky-top shadow-sm">
    <div class="container">
        <a class="btn btn-outline-dark rounded-pill px-3 py-1" href="menu.php">
            <i class="bi bi-chevron-left"></i> เลือกเมนูเพิ่ม
        </a>
        <span class="fw-bold fs-5">ตะกร้าสินค้า</span>
    </div>
</nav>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card cart-card p-4">
                <h5 class="fw-bold mb-4">รายการสินค้าทั้งหมด (<?= $total_qty ?> ชิ้น)</h5>
                
                <?php if (empty($cart_items)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x display-1 text-muted opacity-25"></i>
                        <p class="text-muted mt-3">ตะกร้าของคุณยังว่างเปล่า</p>
                        <a href="menu.php" class="btn btn-dark rounded-pill px-4">ไปเลือกเมนูฟินๆ กันเลย</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <thead>
                                <tr class="text-muted small border-bottom">
                                    <th colspan="2">สินค้า</th>
                                    <th>ราคา</th>
                                    <th class="text-center">จำนวน</th>
                                    <th>รวม</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach ($cart_items as $id => $qty): 
                                    // ดึงข้อมูลสินค้าจากฐานข้อมูล
                                    $id = mysqli_real_escape_string($conn, $id);
                                    $sql = "SELECT * FROM tb_menu WHERE id_menu = '$id'";
                                    $result = mysqli_query($conn, $sql);
                                    if ($row = mysqli_fetch_assoc($result)):
                                        $subtotal = $row['price_menu'] * $qty;
                                        $total_price += $subtotal;
                                ?>
                                <tr>
                                    <td>
                                        <img src="img/<?= htmlspecialchars($row['img_menu']) ?>" 
                                             class="product-img shadow-sm" 
                                             onerror="this.src='https://placehold.co/100x100?text=TatoFun'">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($row['name_menu']) ?></div>
                                        <div class="text-muted small">TatoFun Signature</div>
                                    </td>
                                    <td>฿<?= number_format($row['price_menu'], 0) ?></td>
                                    <td style="width: 100px;">
                                        <div class="text-center bg-light py-1 rounded-3 fw-bold">
                                            <?= $qty ?>
                                        </div>
                                    </td>
                                    <td class="fw-bold text-dark">฿<?= number_format($subtotal, 0) ?></td>
                                    <td>
                                        <a href="cart_action.php?action=remove&id=<?= $id ?>" 
                                           class="btn btn-sm btn-outline-danger border-0" 
                                           onclick="return confirm('ต้องการลบ <?= htmlspecialchars($row['name_menu']) ?> ใช่หรือไม่?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endif; endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card cart-card p-4 bg-white sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-4">สรุปยอดคำสั่งซื้อ</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">ยอดรวมสินค้า</span>
                    <span>฿<?= number_format($total_price, 0) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted">ค่าจัดส่ง</span>
                    <span class="text-success fw-bold">ฟรี</span>
                </div>
                <hr class="opacity-50">
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold fs-5">ยอดชำระสุทธิ</span>
                    <span class="fw-bold fs-5 text-dark">฿<?= number_format($total_price, 0) ?></span>
                </div>
                
                <button class="btn btn-checkout w-100 py-3 shadow-sm mb-3" 
                        <?= empty($cart_items) ? 'disabled' : '' ?> 
                        onclick="window.location.href='checkout.php'">
                    สั่งซื้อเลย (<?= $total_qty ?> ชิ้น)
                </button>
                <div class="text-center mt-2">
                    <img src="img/Logo.png" alt="Logo" style="height: 30px; opacity: 0.5;">
                </div>
            </div>
        </div>
    </div>
</div>

<div style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
    <a href="cart.php" class="btn btn-dark rounded-pill py-2 px-4 shadow-lg border-2 border-warning">
        <i class="bi bi-cart3 me-2"></i> ตะกร้าของฉัน 
        <span class="badge cart-badge rounded-circle px-2"><?= $total_qty ?></span>
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>