<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: login_ad.php");
    exit();
}
?>

<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tatofun Admin - Fresh & Fun Fries</title>
    
<link rel="icon" type="image/png" href="img_ad/logo.png">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .btn-black {
            background-color: #000;
            border: 1px solid #000;
            color: white;
        }
        .btn-black:hover {
            background-color: #333;
            color: white;
            border: 1px solid #333;
        }
        /* ควบคุมขนาดรูปสไลด์ให้พอดีจอ */
        .carousel-item img {
            object-fit: cover;
            height: 500px;
        }
        @media (max-width: 768px) {
            .carousel-item img {
                height: 250px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top" style="background-color: #ffb300;">
        <div class="container"> 
            <a class="navbar-brand" href="#">
                <img src="img_ad/logo.png" alt="Logo" width="60" height="60" class="d-inline-block align-text-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-white active" aria-current="page" href="#">หน้าหลัก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">จัดการเมนู</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            โปรโมชั่น
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">ลดราคาพิเศษ</a></li>
                            <li><a class="dropdown-item" href="#">สะสมแต้ม</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="ค้นหาเมนู..." aria-label="Search">
                    <button class="btn btn-black" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="5000">
                <img src="img_ad/lo1.png" class="d-block w-100" alt="Banner 1">
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="img_ad/lo2.png" class="d-block w-100" alt="Banner 2">
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="img_ad/lo3.png" class="d-block w-100" alt="Banner 3">
            </div>
        </div> 
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div> 

    
    <div class="container my-5 text-center">
        <h2 class="fw-bold">ยินดีต้อนรับสู่โหมดผู้ดูแลระบบ (TatoFun) 🍟</h2>
        <p class="lead">คุณสามารถแก้ไขรูปภาพและจัดการรายการอาหารได้ที่หน้านี้</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>