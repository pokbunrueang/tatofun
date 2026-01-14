<?php 
session_start(); 
include 'config.php'; 

// 1. ระบบดักจับ Role (ตรวจสอบสิทธิ์)
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') { header("Location: admin/index_ad.php"); exit(); }
    if ($_SESSION['role'] == 'staff') { header("Location: staff/index_st.php"); exit(); }
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
$banner3_path = !empty($images[4]) ? "admin/img_ad/".$images[4] : "img/no3.png";
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

        body { font-family: 'Kanit', sans-serif; background-color: var(--tato-light); color: var(--tato-dark); }
        
        /* Navbar Custom - ปรับให้ชิดขอบและดูสะอาดตา */
        .navbar { 
            background-color: var(--tato-yellow) !important; 
            border-bottom: 3px solid var(--tato-orange);
            padding-left: 1rem; 
            padding-right: 1rem;
        }
        .nav-link { font-weight: 500; color: var(--tato-dark) !important; transition: 0.3s; }
        .nav-link:hover { color: white !important; }

        .floating-cart {
            position: fixed; bottom: 30px; right: 30px; z-index: 1000;
            background: var(--tato-dark); color: white; padding: 15px 25px;
            border-radius: 50px; box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            text-decoration: none; transition: 0.3s;
        }
        .floating-cart:hover { transform: scale(1.1); color: var(--tato-yellow); }

        .carousel-item img { height: 450px; object-fit: cover; }
        @media (max-width: 768px) { .carousel-item img { height: 250px; } }
    </style>
</head>
<body>

    <a href="cart.php" class="floating-cart">
        <i class="bi bi-cart-fill me-2"></i> ตะกร้าของฉัน <span class="badge bg-warning text-dark ms-1">0</span>
    </a>

    <nav class="navbar navbar-expand-lg sticky-top shadow-sm">
        <div class="container-fluid"> <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="<?php echo $logo_path; ?>" width="45" height="45" class="me-2 rounded-circle bg-white shadow-sm">
                <span class="fw-bold fs-4 text-dark">Tato<span class="text-white">Fun</span></span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-3 ps-lg-3">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="bi bi-house-door-fill me-1"></i>หน้าแรก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="menu.php">เมนูอาหาร</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="promotion.php">โปรโมชั่น</a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <?php if(!isset($_SESSION['fullname'])): ?>
                        <li class="nav-item">
                            <a href="login.php" class="btn btn-dark rounded-pill px-4 shadow-sm">
                                <i class="bi bi-box-arrow-in-right me-1"></i> เข้าสู่ระบบ
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fw-bold" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> <?= $_SESSION['fullname'] ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-power me-2"></i>ออกจากระบบ</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-0">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="<?= $banner1_path ?>" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="<?= $banner2_path ?>" class="d-block w-100">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle"></span>
            </button>
        </div>
    </div>

    <div class="container py-5" id="menu-section">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">เลือกความ <span class="text-warning">ฟิน</span> ในแบบคุณ</h2>
            <div class="mx-auto bg-warning" style="height: 4px; width: 60px; border-radius: 2px;"></div>
        </div>
        
        <div class="row g-4">
            <?php
            $sql_menu = "SELECT * FROM tb_menu";
            $res_menu = mysqli_query($conn, $sql_menu);
            if (mysqli_num_rows($res_menu) > 0) {
                while($menu = mysqli_fetch_assoc($res_menu)):
                    $m_img = !empty($menu['img_menu']) ? "admin/img_menu/".$menu['img_menu'] : "img/default.png";
            ?>
                <?php endwhile; } else { ?>
                <div class='col-12 text-center'><p class='text-muted'>ยังไม่มีเมนูในขณะนี้</p></div>
            <?php } ?>
        </div>
    </div>

    <footer class="bg-white pt-5 pb-3 mt-5" style="border-top: 5px solid var(--tato-yellow);">
        <div class="container text-center text-md-start">
            <div class="row gy-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start mb-3">
                        <img src="<?php echo $logo_path; ?>" width="40" height="40" class="me-2 rounded-circle shadow-sm">
                        <h3 class="fw-bold mb-0">Tato<span style="color: var(--tato-orange);">Fun</span></h3>
                    </div>
                    <p class="text-muted small">ความสุขคำโตๆ กับมันฝรั่งทอดคุณภาพดีที่เราตั้งใจมอบให้คุณในทุกๆ วัน</p>
                </div>
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-3 border-start border-warning border-4 ps-2">ติดต่อเรา</h5>
                    <p class="text-muted small mb-1"><i class="bi bi-geo-alt-fill me-2"></i>สุขุมวิท ศรีราชา จ.ชลบุรี</p>
                    <p class="text-muted small mb-1"><i class="bi bi-telephone-fill me-2"></i>099-999-9999</p>
                </div>
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-3 border-start border-warning border-4 ps-2">ติดตามเรา</h5>
                    <div class="d-flex gap-2 justify-content-center justify-content-lg-start">
                        <a href="#" class="btn btn-outline-warning btn-sm rounded-circle"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-warning btn-sm rounded-circle"><i class="bi bi-tiktok text-dark"></i></a>
                        <a href="#" class="btn btn-outline-warning btn-sm rounded-circle"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5 text-muted small border-top pt-3">
                <p>© 2026 TatoFun. Fresh & Fun Fries. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>