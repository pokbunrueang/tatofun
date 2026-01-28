<?php
session_start();
// แก้ไข path ให้ถูกต้องเพื่อให้เชื่อมต่อฐานข้อมูลได้ (ไฟล์อยู่ในระดับเดียวกัน)
include 'config.php'; 
// 1. คำนวณจำนวนชิ้นในตะกร้า
$total_qty = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        if (is_array($item)) {
            $total_qty += (int)($item['qty'] ?? 0);
        } else {
            $total_qty += (int)$item;
        }
    }
}
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
        :root { --tato-orange: #f57c00; --tato-yellow: #ffb300; }
        body { background-color: #fdfaf0; font-family: 'Kanit', sans-serif; color: #444; }
        .menu-header { 
            background: linear-gradient(135deg, var(--tato-orange), var(--tato-yellow)); 
            color: white; padding: 60px 0; border-radius: 0 0 80px 80px;
            box-shadow: 0 10px 30px rgba(245, 124, 0, 0.2);
        }
        .card-menu { 
            border: none; border-radius: 30px; transition: all 0.4s ease; 
            background: #fff; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        .card-menu:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .img-container { overflow: hidden; position: relative; height: 200px; }
        .card-menu img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .card-menu:hover img { transform: scale(1.1); }
        .btn-tato {
            background: linear-gradient(to right, var(--tato-orange), var(--tato-yellow));
            color: white; border: none; border-radius: 50px; font-weight: 600; padding: 10px;
        }
        .cart-float { position: fixed; bottom: 30px; right: 30px; z-index: 1000; }
        .cart-btn {
            background: #2c3e50; color: white; border-radius: 50px; padding: 15px 25px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2); text-decoration: none; display: flex; align-items: center;
        }
        .modal-content { border-radius: 30px; border: none; }
        .topping-item { border: 2px solid #f8f9fa; border-radius: 15px; margin-bottom: 10px; cursor: pointer; transition: 0.2s; }
        .topping-item:hover { border-color: var(--tato-orange); background: #fffcf5; }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white sticky-top shadow-sm py-3">
    <div class="container">
        <a class="btn btn-outline-dark rounded-pill px-4" href="index.php">
            <i class="bi bi-arrow-left me-2"></i> กลับหน้าหลัก
        </a>
    </div>
</nav>

<div class="menu-header text-center mb-5">
    <div class="container">
        <h1 class="display-5 fw-bold">เลือกความ <span class="text-dark">ฟิน</span> ในแบบคุณ</h1>
        <p class="lead opacity-75">โรยผงเปลือกผักผลไม้ เพิ่มคุณค่าทางอาหาร 🍎🥕</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <?php
        // ดึงข้อมูลเมนูทั้งหมดมาแสดง (ใช้ชื่อคอลัมน์ menu_stock ตามจริงใน DB)
        $sql = "SELECT * FROM tb_menu ORDER BY id_menu DESC";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0):
            while($row = mysqli_fetch_assoc($result)):
                $img_path = "admin/img_ad/" . $row['img_menu'];
                // ตรวจสอบสถานะจากคอลัมน์ menu_stock (1=มีของ, 0=หมด)
                $is_available = (isset($row['menu_stock']) && $row['menu_stock'] == 1); 
        ?>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card card-menu h-100 <?= !$is_available ? 'opacity-75' : '' ?>">
                <div class="img-container">
                    <img src="<?= $img_path ?>" onerror="this.src='img/no1.png';" style="<?= !$is_available ? 'filter: grayscale(1);' : '' ?>">
                </div>
                <div class="card-body d-flex flex-column p-4">
                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($row['name_menu']) ?></h6>
                    <p class="text-muted small">สูตรพิเศษจาก TatoFun</p>
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <span class="fs-5 fw-bold text-dark">฿<?= number_format($row['price_menu'], 0) ?></span>
                        
                        <?php if($is_available): ?>
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <button class="btn btn-tato btn-sm px-3" 
                                        onclick="openToppingModal('<?= $row['id_menu'] ?>', '<?= htmlspecialchars($row['name_menu']) ?>')">
                                    + สั่งเลย
                                </button>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-light btn-sm rounded-pill px-3">ล็อกอิน</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-sm rounded-pill px-3" disabled>หมด</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; else: ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-emoji-frown fs-1 text-muted"></i>
                <h4 class="mt-3 text-muted">ไม่พบรายการเมนู</h4>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="toppingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="cart_action.php?action=add" method="POST">
                <input type="hidden" name="id" id="modal_id_menu">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0" id="modal_menu_name">ชื่อเมนู</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <label class="form-label fw-bold mb-3 text-warning">✨ เลือกผงโรยสุขภาพ (ฟรี):</label>
                    <div class="topping-item p-3 d-flex align-items-center">
                        <input class="form-check-input me-3" type="radio" name="topping" id="t1" value="Apple Peel" checked>
                        <label class="form-check-label w-100" for="t1">
                            <strong>🍎 ผงเปลือกแอปเปิ้ล</strong><br>
                            <small class="text-muted">ต้านอนุมูลอิสระและเพิ่มใยอาหาร</small>
                        </label>
                    </div>
                    <div class="topping-item p-3 d-flex align-items-center">
                        <input class="form-check-input me-3" type="radio" name="topping" id="t2" value="Carrot Peel">
                        <label class="form-check-label w-100" for="t2">
                            <strong>🥕 ผงเปลือกแครอท</strong><br>
                            <small class="text-muted">เบต้าแคโรทีนสูง บำรุงสายตา</small>
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="submit" class="btn btn-tato w-100 py-3">ใส่ตะกร้าเลย!</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if(isset($_SESSION['user_id']) && $total_qty > 0): ?>
<div class="cart-float">
    <a href="cart.php" class="cart-btn shadow-lg">
        <i class="bi bi-cart3 me-2 text-warning"></i>
        ตะกร้า <span class="ms-2 badge bg-warning text-dark rounded-pill"><?= $total_qty ?></span>
    </a>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toppingModal = new bootstrap.Modal(document.getElementById('toppingModal'));
    function openToppingModal(id, name) {
        document.getElementById('modal_id_menu').value = id;
        document.getElementById('modal_menu_name').innerText = name;
        toppingModal.show();
    }
</script>
</body>
</html>