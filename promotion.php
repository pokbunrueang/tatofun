<?php
session_start();
include 'config.php'; // เชื่อมต่อฐานข้อมูล db_tatofun
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>โปรโมชั่นสุดคุ้ม - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fdfdfd; font-family: 'Kanit', sans-serif; }
        .navbar { background-color: #fff !important; padding: 15px 0; }
        .promo-header { 
            background: linear-gradient(135deg, #ffc107, #ff9800); 
            color: white; 
            padding: 50px 0; 
            border-radius: 0 0 50px 50px; 
            margin-bottom: 40px;
        }
        .card-promo { 
            border: none; 
            border-radius: 25px; 
            overflow: hidden; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.08); 
            transition: 0.3s;
        }
        .card-promo:hover { transform: scale(1.02); }
        .card-promo img { 
            width: 100%; 
            height: 250px; 
            object-fit: cover; 
        }
        .promo-tag {
            background-color: #dc3545;
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.8rem;
            position: absolute;
            top: 15px;
            right: 15px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-light sticky-top shadow-sm">
    <div class="container">
        <a class="btn btn-outline-dark rounded-pill px-3 py-1" href="index.php">
            <i class="bi bi-chevron-left"></i> กลับหน้าหลัก
        </a>
        <div class="fw-bold fs-5">Tato <span class="text-warning">Promotion</span></div>
    </div>
</nav>

<div class="promo-header text-center">
    <div class="container">
        <h1 class="fw-bold">โปรโมชั่นสุด <span class="text-dark">ฟิน</span></h1>
        <p class="mb-0 opacity-75">รวมดีลดีๆ สำหรับคนรักมันฝรั่งทอด-มันฝรั่งย่าง</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <?php
        // ดึงข้อมูลจากตารางโปรโมชั่น
        $sql = "SELECT * FROM tb_promotions ORDER BY pro_id DESC";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card card-promo position-relative">
                <span class="promo-tag fw-bold shadow-sm">Hot Deal!</span>
                <img src="img/<?= htmlspecialchars($row['pro_img']) ?>" 
                     alt="<?= htmlspecialchars($row['pro_name']) ?>"
                     onerror="this.src='https://placehold.co/600x400?text=Promotion'">
                
                <div class="card-body p-4 text-center">
                    <h4 class="fw-bold text-dark mb-2"><?= htmlspecialchars($row['pro_name']) ?></h4>
                    <p class="text-muted small mb-0"><?= htmlspecialchars($row['pro_detail']) ?></p>
                </div>
            </div>
        </div>
        <?php 
            }
        } else {
            // กรณีไม่มีข้อมูลโปรโมชั่น
            echo "<div class='col-12 text-center py-5'>
                    <i class='bi bi-megaphone display-1 text-muted opacity-25'></i>
                    <p class='text-muted mt-3'>ยังไม่มีโปรโมชั่นใหม่ในขณะนี้</p>
                  </div>";
        }
        ?>
    </div>
</div>

<div style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
    <a href="cart.php" class="btn btn-dark rounded-pill py-2 px-4 shadow-lg border-2 border-warning">
        <i class="bi bi-cart3 me-2"></i> ตะกร้าของฉัน 
        <span class="badge bg-warning text-dark"><?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?></span>
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>