<?php 
session_start(); 
include 'config.php'; 

// 1. ระบบดักจับ Role
if (isset($_SESSION['role']) && $_SESSION['role'] == 'staff') { 
    header("Location: staff/index_st.php"); 
    exit(); 
} 

// 2. ดึงข้อมูลโลโก้และแบนเนอร์
$sql = "SELECT * FROM tb_logobanner";
$result = mysqli_query($conn, $sql);
$images = [];
while($row = mysqli_fetch_assoc($result)) {
    $images[$row['id_lb']] = $row['name_lb'];
}

$logo_path    = !empty($images[1]) ? "admin/img_ad/".$images[1] : "img/Logo.png";
$banner1_path = !empty($images[2]) ? "admin/img_ad/".$images[2] : "img/no1.png";
$banner2_path = !empty($images[3]) ? "admin/img_ad/".$images[3] : "img/no2.png";
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TatoFun - Fresh & Fun Fries</title>
    <link rel="icon" href="<?php echo $logo_path; ?>">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --tato-yellow: #ffca28;
            --tato-orange: #f57c00;
            --tato-dark: #263238;
            --tato-light: #fffbf0;
        }

        body { font-family: 'Kanit', sans-serif; background-color: var(--tato-light); color: var(--tato-dark); overflow-x: hidden; }
        
        /* 🚀 Navbar Custom */
        .navbar { 
            background-color: var(--tato-yellow) !important; 
            border-bottom: 4px solid var(--tato-orange); 
            padding: 0.8rem 0; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
        }

        .navbar > .container-fluid { max-width: 95%; }

        .nav-link { 
            font-size: 1.15rem; 
            font-weight: 600; 
            color: var(--tato-dark) !important; 
            margin: 0 10px; 
            transition: all 0.3s ease;
        }

        .nav-link:hover { color: white !important; transform: translateY(-3px); }

        .btn-login-custom {
            background: var(--tato-dark); color: white !important;
            border-radius: 50px; padding: 8px 25px !important;
            font-weight: 600; box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            text-decoration: none; transition: 0.3s;
        }

        .btn-login-custom:hover { background: #000; transform: scale(1.05); color: var(--tato-yellow) !important; }

        /* 🌟 Menu Card & Hover Effects */
        .menu-card { 
            border: none; border-radius: 25px; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            background: white; overflow: hidden; 
        }
        .menu-card:hover { transform: translateY(-15px) scale(1.05); box-shadow: 0 25px 50px rgba(0,0,0,0.15) !important; }

        .floating-cart-btn {
            position: fixed; bottom: 30px; right: 30px; z-index: 1000;
            border-radius: 50px; padding: 12px 25px;
            background: var(--tato-dark); color: white;
            border: 2px solid var(--tato-yellow);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
        }
        .floating-cart-btn:hover { transform: scale(1.1) rotate(-3deg); color: var(--tato-yellow); }

        .carousel-item img { height: 450px; object-fit: cover; }
        @media (max-width: 768px) { .carousel-item img { height: 250px; } }
    </style>
</head>
<body>

    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="cart.php" class="floating-cart-btn">
            <i class="bi bi-cart-fill me-2 text-warning"></i> ตะกร้าของฉัน 
            <span class="badge bg-warning text-dark ms-1 rounded-pill">
                <?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>
            </span>
        </a>
    <?php endif; ?>

    <nav class="navbar navbar-expand-lg sticky-top shadow-sm">
        <div class="container-fluid px-lg-5"> 
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="<?php echo $logo_path; ?>" width="55" height="55" class="me-2 rounded-circle bg-white shadow-sm" style="border: 2px solid var(--tato-orange);">
                <span class="fw-bold fs-3 text-dark">Tato<span class="text-white">Fun</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0"> 
                    <li class="nav-item"><a class="nav-link active" href="index.php">หน้าแรก</a></li>
                    <li class="nav-item"><a class="nav-link" href="menu.php">เมนูอาหาร</a></li>
                    <li class="nav-item"><a class="nav-link" href="promotion.php">โปรโมชั่น</a></li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <?php if(!isset($_SESSION['fullname'])): ?>
                        <a href="login.php" class="btn btn-login-custom">
                            <i class="bi bi-box-arrow-in-right me-1"></i> เข้าสู่ระบบ
                        </a>
                    <?php else: ?>
                        <div class="dropdown">
                            <button class="btn btn-light rounded-pill dropdown-toggle fw-bold shadow-sm px-3 py-2" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> <?= $_SESSION['fullname'] ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2">
                                <li><a class="dropdown-item py-2" href="profile.php"><i class="bi bi-person me-2 text-warning"></i>โปรไฟล์</a></li>
                                
                                <li><a class="dropdown-item py-2" href="order_history.php"><i class="bi bi-bag-check me-2 text-success"></i>ประวัติการสั่งซื้อ</a></li>
                                
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <li><a class="dropdown-item py-2 text-primary" href="admin/index_ad.php"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</a></li>
                                <?php endif; ?>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger" href="logout.php"><i class="bi bi-power me-2"></i>ออกจากระบบ</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-0">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active"><img src="<?= $banner1_path ?>" class="d-block w-100"></div>
                <div class="carousel-item"><img src="<?= $banner2_path ?>" class="d-block w-100"></div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">🍟 เมนู <span style="color:var(--tato-orange)">แนะนำ</span> สำหรับคุณ</h2>
            <p class="text-muted">คัดสรรความอร่อยมาให้คุณโดยเฉพาะ</p>
        </div>
        
        <div class="row g-4">
            <?php
            $sql_menu = "SELECT * FROM tb_menu LIMIT 4";
            $res_menu = mysqli_query($conn, $sql_menu);
            while($row = mysqli_fetch_assoc($res_menu)):
            ?>
            <div class="col-6 col-md-3">
                <div class="card menu-card h-100 shadow-sm text-center p-3">
                    <img src="admin/img_ad/<?= $row['img_menu'] ?>" class="card-img-top rounded-4 mb-3" style="height: 180px; object-fit: cover;" onerror="this.src='img/no1.png';">
                    <h5 class="fw-bold"><?= $row['name_menu'] ?></h5>
                    <p class="text-warning fw-bold fs-5">฿<?= number_format($row['price_menu'], 0) ?></p>

                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="menu.php" class="btn btn-warning w-100 rounded-pill fw-bold py-2">
                            <i class="bi bi-magic me-1"></i> เลือกท็อปปิ้ง & สั่งเลย
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-secondary w-100 rounded-pill fw-bold btn-sm py-2">
                            ล็อกอินเพื่อสั่งซื้อ
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer class="bg-white pt-5 pb-3 mt-5" style="border-top: 5px solid var(--tato-yellow); box-shadow: 0 -5px 20px rgba(0,0,0,0.05);">
        <div class="container text-center text-md-start">
            <div class="row gy-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start mb-3">
                        <img src="<?php echo $logo_path; ?>" width="45" height="45" class="me-2 rounded-circle shadow-sm" style="border: 2px solid var(--tato-yellow);">
                        <h3 class="fw-bold mb-0">Tato<span style="color: var(--tato-orange);">Fun</span></h3>
                    </div>
                    <p class="text-muted small">ความสุขคำโตๆ กับมันฝรั่งทอดคุณภาพดีที่เราตั้งใจมอบให้คุณในทุกๆ วัน อร่อย สนุก ไปกับ TatoFun</p>
                </div>
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-3 border-start border-warning border-4 ps-2">ติดต่อเรา</h5>
                    <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill me-2 text-warning"></i> สุขุมวิท ศรีราชา จ.ชลบุรี 20110</p>
                    <p class="text-muted small mb-2"><i class="bi bi-telephone-fill me-2 text-warning"></i> 099-999-9999</p>
                </div>
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-3 border-start border-warning border-4 ps-2">ติดตามเรา</h5>
                    <div class="d-flex gap-2 justify-content-center justify-content-lg-start">
                        <a href="#" class="btn btn-outline-warning btn-sm rounded-circle shadow-sm"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-warning btn-sm rounded-circle shadow-sm"><i class="bi bi-tiktok text-dark"></i></a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5 text-muted small border-top pt-3">
                <p>© 2026 <strong>TatoFun</strong>. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>