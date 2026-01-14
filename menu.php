<?php
session_start();
include 'config.php'; // เชื่อมต่อฐานข้อมูล tb_menu
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เมนูทั้งหมด - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { background-color: #fdfdfd; font-family: 'Kanit', sans-serif; }
        .navbar { background-color: #fff !important; padding: 15px 0; }
        .menu-header { background: linear-gradient(135deg, #ffc107, #ff9800); color: white; padding: 40px 0; border-radius: 0 0 50px 50px; }
        .card-menu { border: none; border-radius: 20px; transition: 0.3s; overflow: hidden; background: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .card-menu:hover { transform: translateY(-8px); box-shadow: 0 12px 25px rgba(0,0,0,0.1); }
        .card-menu img { height: 200px; object-fit: cover; width: 100%; background-color: #eee; }
        .price-tag { color: #2c3e50; font-size: 1.2rem; font-weight: 600; }
        .btn-add { background-color: #2c3e50; color: white; border-radius: 10px; padding: 5px 15px; border: none; font-size: 0.9rem; transition: 0.2s; }
        .btn-add:hover { background-color: #000; color: #ffc107; transform: scale(1.05); }
        .cart-float { position: fixed; bottom: 20px; right: 20px; z-index: 1000; transition: 0.3s; }
        .cart-float:hover { transform: scale(1.1); }
    </style>
</head>
<body>

<nav class="navbar navbar-light sticky-top shadow-sm">
    <div class="container">
        <a class="btn btn-outline-dark rounded-pill px-3 py-1" href="index.php">
            <i class="bi bi-chevron-left"></i> กลับหน้าหลัก
        </a>
        <div class="fw-bold d-none d-md-block">TatoFun Menu</div>
    </div>
</nav>

<div class="menu-header text-center mb-5">
    <div class="container">
        <h1 class="fw-bold">เลือกความ <span class="text-dark">ฟิน</span> ในแบบคุณ</h1>
        <p class="mb-0 opacity-75">รายการอาหารและของทานเล่นทั้งหมด</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <?php
        $sql = "SELECT * FROM tb_menu ORDER BY id_menu DESC";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card card-menu h-100 text-center">
                <img src="img/<?= htmlspecialchars($row['img_menu']) ?>" 
                     alt="<?= htmlspecialchars($row['name_menu']) ?>"
                     onerror="this.src='https://placehold.co/400x400?text=TatoFun'">
                
                <div class="card-body d-flex flex-column p-3">
                    <h5 class="fw-bold mb-3"><?= htmlspecialchars($row['name_menu']) ?></h5>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <span class="price-tag">฿<?= number_format($row['price_menu'], 0) ?></span>
                        <a href="cart_action.php?action=add&id=<?= $row['id_menu'] ?>" 
                           class="btn btn-add" 
                           onclick="return notifyAdd('<?= htmlspecialchars($row['name_menu']) ?>')">
                            + สั่งเลย
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            }
        } else {
            echo "<div class='col-12 text-center py-5'><p class='text-muted'>ยังไม่มีรายการอาหาร</p></div>";
        }
        ?>
    </div>
</div>

<div class="cart-float">
    <a href="cart.php" class="btn btn-dark rounded-pill py-2 px-4 shadow-lg border-2 border-warning">
        <i class="bi bi-cart3 me-2"></i> ตะกร้าของฉัน 
        <span class="badge bg-warning text-dark px-2">
            <?php 
                $total_qty = 0;
                if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                    foreach($_SESSION['cart'] as $qty) {
                        $total_qty += $qty; // บวกรวมจำนวนชิ้นทั้งหมด
                    }
                }
                echo $total_qty; // จะแสดงเลข 13 ตามตัวอย่างของคุณ
            ?>
        </span>
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ฟังก์ชันแจ้งเตือนเมื่อกดปุ่มสั่งซื้อ
function notifyAdd(name) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true
    });

    Toast.fire({
        icon: 'success',
        title: 'เพิ่ม ' + name + ' ลงตะกร้าแล้ว'
    });
    return true; // ปล่อยให้ลิงก์ทำงานต่อเพื่อไปที่ cart_action.php
}
</script>

</body>
</html>