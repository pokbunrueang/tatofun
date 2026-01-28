<?php
session_start();
include 'config.php'; 

// 1. ตรวจสอบสิทธิ์การเข้าถึง
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ประวัติการสั่งซื้อ - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --tato-orange: #f57c00; --tato-yellow: #ffb300; }
        body { background-color: #fdfaf0; font-family: 'Kanit', sans-serif; color: #444; }
        
        /* สไตล์ Card ใหม่ให้ดูนุ่มนวล */
        .order-card { 
            border: none; border-radius: 25px; transition: all 0.3s ease; 
            background: white; margin-bottom: 25px; overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        .order-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 15px 30px rgba(245, 124, 0, 0.1); 
        }

        .status-badge { 
            border-radius: 50px; padding: 6px 16px; font-weight: 600; font-size: 0.8rem;
        }
        
        .btn-detail {
            background: linear-gradient(45deg, var(--tato-orange), var(--tato-yellow));
            color: white; border: none; border-radius: 50px; font-weight: 600;
            padding: 8px 25px; transition: 0.3s;
        }
        .btn-detail:hover { color: white; opacity: 0.9; transform: scale(1.05); }
        
        .order-icon {
            width: 50px; height: 50px; background: #fff9f0;
            border-radius: 15px; display: flex; align-items: center;
            justify-content: center; color: var(--tato-orange); font-size: 1.5rem;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
        <div class="mb-3 mb-md-0">
            <a href="profile.php" class="text-decoration-none text-muted mb-2 d-inline-block">
                <i class="bi bi-arrow-left me-1"></i> กลับหน้าโปรไฟล์
            </a>
            <h2 class="fw-bold mb-0">📜 ประวัติความ <span class="text-warning">ฟิน</span></h2>
        </div>
        <div class="text-end">
             <span class="badge bg-white text-dark shadow-sm rounded-pill px-3 py-2">
                ผู้ใช้งาน: <?= htmlspecialchars($_SESSION['username'] ?? 'Member') ?>
             </span>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <?php
            // ดึงข้อมูลการสั่งซื้อ
            $sql = "SELECT * FROM tb_orders WHERE user_id = '$user_id' ORDER BY id_order DESC";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0):
                while($row = mysqli_fetch_assoc($result)):
            ?>
            <div class="card order-card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="order-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                        <div class="col">
                            <span class="text-muted small">หมายเลขคำสั่งซื้อ: #<?= $row['id_order'] ?></span>
                            <h5 class="fw-bold mb-0 mt-1">ยอดรวม: ฿<?= number_format($row['total_price'], 2) ?></h5>
                        </div>
                        <div class="col-md-auto mt-3 mt-md-0 text-md-end">
                            <span class="badge bg-success status-badge mb-2 d-inline-block">
                                <i class="bi bi-check-circle-fill me-1"></i> ชำระเงินแล้ว
                            </span>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($row['order_date'])) ?>
                            </p>
                        </div>
                    </div>
                    <hr class="my-4 opacity-25">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">ขอบคุณที่เลือก TatoFun 🍎🥕</span>
                        <a href="order_detail.php?id=<?= $row['id_order'] ?>" class="btn btn-detail">
                            ดูรายละเอียด
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; else: ?>
            <div class="text-center py-5">
                <img src="https://cdn-icons-png.flaticon.com/512/11329/11329073.png" width="120" class="mb-4 opacity-50">
                <h4 class="text-muted">ยังไม่มีรายการสั่งซื้อในขณะนี้</h4>
                <a href="menu.php" class="btn btn-warning rounded-pill px-4 mt-3 text-white fw-bold">เริ่มสั่งอาหารเลย</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>