<?php 
session_start(); 
include 'config.php'; 

// ดึงข้อมูลโลโก้และแบนเนอร์
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
        body { font-family: 'Kanit', sans-serif; background-color: #fffdfb; }
        .btn-black { background-color: #000; border: 1px solid #000; color: white; border-radius: 50px; }
        .btn-black:hover { background-color: #333; color: white; }
        .carousel-item img { object-fit: cover; height: 500px; }
        @media (max-width: 768px) { .carousel-item img { height: 250px; } }

        /* Navbar Styling */
        .navbar { box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .nav-link { font-weight: 400; transition: 0.3s; color: white !important; }
        .nav-link:hover { opacity: 0.8; }
        .search-box { border-radius: 50px; padding-left: 15px; }

        footer { border-top: 1px solid #eee; }
        .footer-title { border-left: 4px solid #ffb300; padding-left: 12px; font-weight: 700; margin-bottom: 20px; }
        .contact-item { display: flex; align-items: center; margin-bottom: 12px; color: #6c757d; font-size: 0.95rem; }
        .contact-item i { width: 32px; height: 32px; background: #fff8e1; color: #ffb300; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px; flex-shrink: 0; }
        
        .social-link { width: 40px; height: 40px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; background: #f8f9fa; color: #333; text-decoration: none; transition: 0.3s; font-size: 1.2rem; }
        .social-link:hover { background: #ffb300; color: white; transform: translateY(-3px); }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top" style="background-color: #ffb300;">
        <div class="container"> 
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="<?php echo $logo_path; ?>" alt="Logo" width="50" height="50" class="me-2 rounded-circle shadow-sm bg-white">
                <span class="fw-bold text-white">TatoFun</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="index.php">หน้าหลัก</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#">เมนู</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">โปรโมชั่น</a>
                        <ul class="dropdown-menu border-0 shadow mt-2">
                            <li><a class="dropdown-item" href="#">ลดราคาพิเศษ</a></li>
                            <li><a class="dropdown-item" href="#">สะสมแต้ม</a></li>
                        </ul>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <form class="d-flex" role="search" action="search.php" method="GET">
                        <div class="input-group">
                            <input class="form-control border-0 px-3 shadow-sm" type="search" name="q" placeholder="ค้นหา..." style="border-radius: 50px 0 0 50px; height: 38px;">
                            <button class="btn btn-black px-3" type="submit" style="border-radius: 0 50px 50px 0; height: 38px;">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>

                    <?php if(isset($_SESSION['role'])): ?>
                        <div class="dropdown shadow-sm">
                            <button class="btn btn-light dropdown-toggle rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?php echo $_SESSION['fullname']; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-3">
                                <?php if($_SESSION['role'] == 'admin'): ?>
                                    <li><a class="dropdown-item" href="admin/index_ad.php"><i class="bi bi-speedometer2 me-2"></i> หลังบ้านแอดมิน</a></li>
                                <?php elseif($_SESSION['role'] == 'staff'): ?>
                                    <li><a class="dropdown-item" href="staff/index.php"><i class="bi bi-shop me-2"></i> ระบบพนักงาน</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> ออกจากระบบ</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-black px-4 rounded-pill shadow-sm"><i class="bi bi-person-fill"></i> เข้าสู่ระบบ</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    
    <div id="carouselExampleInterval" class="carousel slide shadow-sm" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="5000">
                <img src="<?php echo $banner1_path; ?>" class="d-block w-100" alt="Banner 1">
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="<?php echo $banner2_path; ?>" class="d-block w-100" alt="Banner 2">
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="<?php echo $banner3_path; ?>" class="d-block w-100" alt="Banner 3">
            </div>
        </div> 
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div> 

    <div class="container my-5 text-center">
        <h2 class="fw-bold mb-3">ยินดีต้อนรับสู่ <span style="color: #ffb300;">TatoFun</span></h2>
        <p class="lead text-muted mx-auto" style="max-width: 700px;">
            มันฝรั่งทอดและมันฝรั่งย่าง คัดสรรอย่างดี กรอบนอก นุ่มใน 
            คลุกเคล้ากับท็อปปิ้งรสชาติเข้มข้นที่คุณต้องหลงรัก!
        </p>
        <div class="mt-4">
            <a href="#" class="btn btn-warning btn-lg rounded-pill px-5 fw-bold text-white shadow-sm">สั่งซื้อเลย!</a>
        </div>
    </div>

    <footer class="bg-light pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row justify-content-center gy-4 text-center text-md-start">
                <div class="col-lg-4 col-md-5">
                    <h5 class="footer-title text-dark">ติดต่อเรา</h5>
                    <div class="contact-item justify-content-center justify-content-md-start">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>สุขุมวิท ข้างทางรถไฟ ศรีราชา</span>
                    </div>
                    <div class="contact-item justify-content-center justify-content-md-start">
                        <i class="bi bi-telephone-fill"></i>
                        <span>099-999-9999</span>
                    </div>
                    <div class="contact-item justify-content-center justify-content-md-start">
                        <i class="bi bi-clock-fill"></i>
                        <span>เปิดทุกวัน 09:00 – 20:00 น.</span>
                    </div>
                </div>

                <div class="col-lg-1 d-none d-lg-block"></div>

                <div class="col-lg-4 col-md-5">
                    <h5 class="footer-title text-dark">ติดตามเรา</h5>
                    <p class="text-muted small mb-3">ไม่พลาดข่าวสารและส่วนลดดีๆ ติดตามเราเลย</p>
                    <div class="d-flex gap-3 fs-4 mb-4 justify-content-center justify-content-md-start">
                        <a href="#" class="social-link"><i class="bi bi-facebook text-primary"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-tiktok text-dark"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-instagram text-danger"></i></a>
                    </div>
                    <div class="pt-2 border-top">
                        <p class="mb-0 text-muted small">© 2026 <strong>TatoFun</strong>. Fresh & Fun Fries.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>