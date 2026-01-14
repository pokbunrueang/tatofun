<?php
session_start();
include '../config.php'; 

// ตรวจสอบสิทธิ์ Admin (ถ้ามีระบบ Login)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// 1. ดึงยอดขายรวมจากตาราง tb_orders (เลือกเฉพาะที่ 'สำเร็จแล้ว' เพื่อความแม่นยำของกำไร)
$sql_orders = "SELECT SUM(total_price) as total FROM tb_orders WHERE order_status = 'สำเร็จแล้ว'"; 
$res_orders = mysqli_query($conn, $sql_orders);
$order_sales = mysqli_fetch_assoc($res_orders)['total'] ?? 0;

// 2. ดึงข้อมูลสรุปจาก tb_finance (รายรับ-รายจ่ายทั่วไป)
$sql_income = "SELECT SUM(fin_amount) as total FROM tb_finance WHERE fin_type = 'income'";
$res_income = mysqli_query($conn, $sql_income);
$finance_income = mysqli_fetch_assoc($res_income)['total'] ?? 0;

$sql_expense = "SELECT SUM(fin_amount) as total FROM tb_finance WHERE fin_type = 'expense'";
$res_expense = mysqli_query($conn, $sql_expense);
$expense = mysqli_fetch_assoc($res_expense)['total'] ?? 0;

// 3. คำนวณสรุปยอดรวมทั้งหมด
$total_income = $order_sales + $finance_income; 
$profit = $total_income - $expense;
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายงานสรุปยอดขายรวม - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #fffdf0; font-family: 'Kanit', sans-serif; }
        .card { border: none; border-radius: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .stat-card { border: none; border-radius: 20px; color: white; position: relative; transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .bg-gradient-orange { background: linear-gradient(135deg, #fd7e14, #ffc107); }
        .bg-gradient-green { background: linear-gradient(135deg, #28a745, #20c997); }
        .bg-gradient-red { background: linear-gradient(135deg, #dc3545, #f86d7d); }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-bar-chart-line-fill text-warning"></i> รายงานสรุปภาพรวมธุรกิจ</h3>
        <a href="index_ad.php" class="btn btn-dark rounded-pill px-4 shadow-sm">← กลับหน้าหลัก</a>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card stat-card bg-gradient-orange p-4 h-100">
                <small class="opacity-75">ยอดขายจาก Orders (สำเร็จแล้ว)</small>
                <h2 class="fw-bold mb-0">฿ <?= number_format($order_sales, 2) ?></h2>
                <i class="bi bi-cart-check position-absolute end-0 bottom-0 m-3 opacity-25 fs-1"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-gradient-red p-4 h-100">
                <small class="opacity-75">ค่าใช้จ่ายทั้งหมด (รายจ่ายทั่วไป)</small>
                <h2 class="fw-bold mb-0">฿ <?= number_format($expense, 2) ?></h2>
                <i class="bi bi-dash-circle position-absolute end-0 bottom-0 m-3 opacity-25 fs-1"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-gradient-green p-4 h-100">
                <small class="opacity-75">กำไรสุทธิรวม</small>
                <h2 class="fw-bold mb-0">฿ <?= number_format($profit, 2) ?></h2>
                <i class="bi bi-piggy-bank position-absolute end-0 bottom-0 m-3 opacity-25 fs-1"></i>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card p-4 h-100">
                <h5 class="fw-bold mb-4">สัดส่วนรายได้และรายจ่าย</h5>
                <canvas id="mainChart"></canvas>
            </div>
        </div>
        
        <div class="col-lg-7">
            <div class="card p-4 h-100">
                <h5 class="fw-bold mb-4 text-primary">5 ออเดอร์ล่าสุด</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr><th>ID</th><th>ลูกค้า</th><th>ยอดเงิน</th><th class="text-center">สถานะ</th></tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sql_last_orders = "SELECT * FROM tb_orders ORDER BY order_id DESC LIMIT 5";
                            $res_last_orders = mysqli_query($conn, $sql_last_orders);
                            while($ord = mysqli_fetch_assoc($res_last_orders)):
                                // จัดการสี Badge ภาษาไทย
                                $s = $ord['order_status'];
                                $badge_class = "bg-warning text-dark"; // รอตรวจสอบ
                                if($s == 'กำลังส่ง') $badge_class = "bg-info text-dark";
                                if($s == 'สำเร็จแล้ว') $badge_class = "bg-success";
                            ?>
                            <tr>
                                <td class="fw-bold text-muted">#<?= $ord['order_id'] ?></td>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($ord['cus_name'] ?: 'ไม่ระบุชื่อ') ?></div>
                                    <small class="text-muted"><?= $ord['order_date'] ?></small>
                                </td>
                                <td class="fw-bold text-primary">฿ <?= number_format($ord['total_price'], 2) ?></td>
                                <td class="text-center">
                                    <span class="badge rounded-pill <?= $badge_class ?> px-3"><?= $s ?></span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('mainChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['ยอดขายออเดอร์', 'รายรับทั่วไป', 'รายจ่ายรวม'],
        datasets: [{
            data: [<?= $order_sales ?>, <?= $finance_income ?>, <?= $expense ?>],
            backgroundColor: ['#fd7e14', '#20c997', '#dc3545'],
            hoverOffset: 15
        }]
    },
    options: {
        plugins: { 
            legend: { position: 'bottom', labels: { font: { family: 'Kanit' } } } 
        }
    }
});
</script>
</body>
</html>