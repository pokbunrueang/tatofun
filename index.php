<?php 
session_start(); 
include 'config.php'; 

// 1. ดึงข้อมูลโลโก้และแบนเนอร์จากฐานข้อมูล
$sql = "SELECT * FROM tb_logobanner";
$result = mysqli_query($conn, $sql);
$images = [];
while($row = mysqli_fetch_assoc($result)) {
    $images[$row['id_lb']] = $row['name_lb'];
}

// กำหนด Path รูปภาพ (ถ้าไม่มีให้ใช้ Default)
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
            --tato-bg: #fffbf0;
            --tato-white: #ffffff;
        }

        body { 
            font-family: 'Kanit', sans-serif; 
            background-color: var(--tato-bg);
            color: var(--tato-dark);
        }

        /* Navbar Custom */
        .navbar { 
            background-color: var(--tato-yellow) !important; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .nav-link { font-weight: 500; color: #000 !important; transition: 0.3s; }
        .nav-link:hover { color: var(--tato-orange) !important; transform: translateY(-1px); }

        /* Carousel Styling */
        .carousel { border-radius: 0 0 40px 40px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .carousel-item img { object-fit: cover; height: 550px; }
        @media (max-width: 768px) { .carousel-item img { height: 300px; } }

        /* Welcome Section */
        .welcome-card {
            background: var(--tato-white);
            border-radius: 30px;
            padding: 50px;
            margin-top: -50px;
            position: relative;
            z-index: 10;
            box-shadow: 0 15px 40px rgba(0,0,0,0.05);
        }

        /* Buttons */
        .btn-tato {
            background: linear-gradient(135deg, var(--tato-yellow), var(--tato-orange));
            border: none;
            color: #000;
            font-weight: 600;
            border-radius: 50px;
            padding: 12px 35px;
            transition: all 0.3s ease;
        }
        .btn-tato:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(245, 124, 0, 0.3);
            color: #000;
        }

        .btn-black { 
            background-color: #000; 
            color: white; 
            border-radius: 50px; 
            padding: 8px 20px;
            transition: 0.3s;
        }
        .btn-black:hover { background-color: #333; color: white; transform: scale(1.05); }

        /* Footer */
        footer { background-color: #fff; border-top: 1px solid #eee; }
        .footer-title { border-left: 4px solid var(--tato-orange); padding-left: 12px; font-weight: 700; }
        .contact-item i { 
            width: 35px; height: 35px; 
            background: var(--tato-bg); 
            color: var(--tato-orange); 
            border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; 
            margin-right: 12px;
        }
        .social-link { 
            width: 45px; height: 45px; 
            border-radius: 50%; 
            display: inline-flex; align-items: center; justify-content: center; 
            background: #f8f9fa; color: #333; transition: 0.3s; font-size: 1.3rem;
        }
        .social-link:hover { background: var(--tato-yellow); transform: translateY(-5px); }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top navbar-light">
        <div class="container"> 
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="<?php echo $logo_path; ?>" alt="Logo" width="50" height="50" class="me-2 rounded-circle shadow-sm bg-white">
                <span class="fw-bold fs-4">Tato<span class="text-white">Fun</span></span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active" href="index.php">หน้าหลัก</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">เมนูของเรา</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">โปรโมชั่น</a>
                        <ul class="dropdown-menu border-0 shadow mt-2">
                            <li><a class="dropdown-item" href="#">ลดราคาพิเศษ</a></li>
                            <li><a class="dropdown-item" href="#">สะสมแต้ม</a></li>
                        </ul>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    <form class="d-none d-md-flex" action="search.php" method="GET">
                        <div class="input-group">
                            <input class="form-control border-0 shadow-sm px-3" type="search" name="q" placeholder="ค้นหาเมนู..." style="border-radius: 50px 0 0 50px;">
                            <button class="btn btn-dark px-3" type="submit" style="border-radius: 0 50px 50px 0;">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>

                    <?php if(isset($_SESSION['role'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle rounded-pill px-3 shadow-sm" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> <?php echo $_SESSION['fullname']; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-3">
                                <?php if($_SESSION['role'] == 'admin'): ?>
                                    <li><a class="dropdown-item" href="admin/index_ad.php"><i class="bi bi-speedometer2 me-2"></i> แผงควบคุมแอดมิน</a></li>
                                <?php elseif($_SESSION['role'] == 'staff'): ?>
                                    <li><a class="dropdown-item" href="staff/index_st.php"><i class="bi bi-shop me-2"></i> ระบบพนักงาน</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> ออกจากระบบ</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-black shadow-sm"><i class="bi bi-person-fill me-1"></i> เข้าสู่ระบบ</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="5000">
                <img src="<?php echo $banner1_path; ?>" class="d-block w-100" alt="Special Fries">
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="<?php echo $banner2_path; ?>" class="d-block w-100" alt="Cheesy Fries">
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="<?php echo $banner3_path; ?>" class="d-block w-100" alt="New Promotion">
            </div>
        </div> 
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon p-3 bg-dark bg-opacity-25 rounded-circle"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon p-3 bg-dark bg-opacity-25 rounded-circle"></span>
        </button>
    </div> 

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="welcome-card text-center">
                    <h5 class="text-uppercase text-warning fw-bold mb-2">Welcome to TatoFun</h5>
                    <h1 class="display-4 fw-bold mb-4">มันฝรั่งที่ <span class="text-warning">สนุก</span> ที่สุดในเมือง!</h1>
                    <p class="lead text-muted mx-auto mb-5" style="max-width: 800px;">
                        สัมผัสประสบการณ์ความกรอบนอกนุ่มในของมันฝรั่งเกรดพรีเมียม 
                        คลุกเคล้ากับผงปรุงรสสูตรลับและท็อปปิ้งซอสเยิ้มๆ ที่คุณเลือกเองได้ 
                        สดใหม่ทุกจาน ทอดร้อนทุกออเดอร์!
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="#" class="btn btn-tato btn-lg shadow">เริ่มสั่งอาหารเลย</a>
                        <a href="#" class="btn btn-outline-dark btn-lg rounded-pill px-4">ดูเมนูทั้งหมด</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 text-center text-md-start">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start mb-4">
                        <img src="<?php echo $logo_path; ?>" alt="Logo" width="40" class="me-2 rounded-circle">
                        <h4 class="fw-bold mb-0">TatoFun</h4>
                    </div>
                    <p class="text-muted">ความสุขคำโตๆ กับมันฝรั่งทอดคุณภาพดีที่เราตั้งใจมอบให้คุณในทุกๆ วัน</p>
                    <div class="d-flex gap-2 mt-4 justify-content-center justify-content-md-start">
                        <a href="#" class="social-link"><i class="bi bi-facebook text-primary"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-tiktok text-dark"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-instagram text-danger"></i></a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-title text-dark mb-4">ข้อมูลการติดต่อ</h5>
                    <div class="contact-item d-flex align-items-center mb-3">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>สุขุมวิท ข้างทางรถไฟ ศรีราชา จ.ชลบุรี</span>
                    </div>
                    <div class="contact-item d-flex align-items-center mb-3">
                        <i class="bi bi-telephone-fill"></i>
                        <span>099-999-9999</span>
                    </div>
                    <div class="contact-item d-flex align-items-center mb-3">
                        <i class="bi bi-clock-fill"></i>
                        <span>เปิดให้บริการ: 09:00 – 20:00 น.</span>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 text-center text-lg-start">
                    <h5 class="footer-title text-dark mb-4">รับข่าวสารพิเศษ</h5>
                    <p class="small text-muted mb-4">สมัครสมาชิกเพื่อรับโปรโมชั่นและเมนูใหม่ก่อนใคร</p>
                    <div class="input-group mb-3 shadow-sm">
                        <input type="text" class="form-control border-0" placeholder="อีเมลของคุณ">
                        <button class="btn btn-warning fw-bold" type="button">ติดตาม</button>
                    </div>
                </div>
            </div>
            <div class="border-top mt-5 pt-4 text-center">
                <p class="mb-0 text-muted small">© 2026 <strong>TatoFun</strong>. Fresh & Fun Fries. All Rights Reserved.</p>
            </div>
        </div>
        <div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold"><i class="bi bi-fire text-danger me-2"></i>เมนูแนะนำ <span class="text-warning">ยอดฮิต</span></h2>
        <p class="text-muted">เมนูที่ใครมาก็ต้องสั่ง ลองแล้วจะติดใจ!</p>
    </div>

    <div class="row g-4">
        <?php
        // ดึงข้อมูลเมนู 4 รายการแรกมาแสดงโชว์ (อ้างอิงจาก tb_menu ใน db_tatofun.sql)
        $sql_menu = "SELECT * FROM tb_menu LIMIT 4";
        $res_menu = mysqli_query($conn, $sql_menu);
        
        while($menu = mysqli_fetch_assoc($res_menu)):
            $m_img = !empty($menu['img_menu']) ? "admin/uploads/".$menu['img_menu'] : "img/default.png";
        ?>
        <div class="col-lg-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative">
                <span class="position-absolute top-0 end-0 m-3 badge rounded-pill bg-danger shadow-sm">Hot</span>
                <img src="<?= $m_img ?>" class="card-img-top" alt="<?= $menu['name_menu'] ?>" style="height: 200px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($menu['name_menu']) ?></h5>
                    <p class="text-orange fw-bold fs-5 mb-3">฿<?= number_format($menu['price_menu'], 2) ?></p>
                    <a href="#" class="btn btn-outline-warning rounded-pill w-100 fw-bold">สั่งเลย</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>