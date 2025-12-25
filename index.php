<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tatofun - Fresh & Fun Fries</title>
    <link rel="icon" href="img/Logo.png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* ตกแต่งปุ่ม Search */
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
        /* ปรับความสูงรูป Carousel ไม่ให้ใหญ่เกินไปในจอคอม */
        .carousel-item img {
            object-fit: cover;
            height: 500px; /* ปรับความสูงตามต้องการ */
        }
        @media (max-width: 768px) {
            .carousel-item img {
                height: 250px; /* จอมือถือให้เล็กลง */
            }
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top" style="background-color: #ffb300;">
        <div class="container"> 
            <a class="navbar-brand" href="#">
                <img src="img/Logo.png" alt="Logo" width="60" height="60" class="d-inline-block align-text-top">
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
                        <a class="nav-link text-white" href="#">เมนู</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            โปรโมชั่น
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">ลดราคาพิเศษ</a></li>
                            <li><a class="dropdown-item" href="#">สะสมแต้ม</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">สาขาใกล้คุณ</a></li>
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
                <img src="img/no1.png" class="d-block w-100" alt="Banner 1">
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="img/no2.png" class="d-block w-100" alt="Banner 2">
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="img/no3.png" class="d-block w-100" alt="Banner 3">
            </div>
        </div> <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div> <div class="container my-5 text-center">
        <h2 class="fw-bold">ยินดีต้อนรับสู่ TatoFun 🍟</h2>
        <p class="lead">เฟรนช์ฟรายส์ทอดสดใหม่ กรอบนอก นุ่มใน พร้อมผงปรุงรสสุดฟิน!</p>
    </div>

    <footer class="bg-light pt-5 pb-3 mt-5 border-top">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="fw-bold mb-3">ติดต่อเรา</h5>
                    <div class="d-flex mb-2">
                        <i class="bi bi-geo-alt-fill me-3 fs-5 text-secondary"></i>
                        <span>สุขุมวิท ข้างทางรถไฟ</span>
                    </div>
                    <div class="d-flex mb-2">
                        <i class="bi bi-telephone-fill me-3 fs-5 text-secondary"></i>
                        <span>099-999-9999</span>
                    </div>
                    <div class="d-flex">
                        <i class="bi bi-clock-fill me-3 fs-5 text-secondary"></i>
                        <span>เปิดทุกวัน 09:00 – 20:00 น.</span>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 text-lg-center">
                    <h5 class="fw-bold mb-3">ติดตามเรา</h5>
                    <div class="d-flex justify-content-lg-center gap-3 fs-4">
                        <a href="#" class="text-primary"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-dark"><i class="bi bi-tiktok"></i></a>
                        <a href="#" class="text-danger"><i class="bi bi-instagram"></i></a>
                    </div>
                    <div class="mt-3">
                        <p class="mb-1">FB : <strong>TatoFun</strong>
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
