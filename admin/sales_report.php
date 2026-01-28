<?php 
session_start();
include '../config.php'; 

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php"); exit();
}

// 1. ดึงยอดขายรวมจากออเดอร์ (สำเร็จแล้ว)
$sql_orders = "SELECT SUM(total_price) as total FROM tb_orders WHERE order_status = 'สำเร็จแล้ว'"; 
$res_orders = mysqli_query($conn, $sql_orders);
$order_sales = mysqli_fetch_assoc($res_orders)['total'] ?? 0;

// 2. ดึงรายรับอื่นๆ จาก tb_finance (ถ้ามี)
$sql_income = "SELECT SUM(fin_amount) as total FROM tb_finance WHERE fin_type = 'income'";
$res_income = mysqli_query($conn, $sql_income);
$finance_income = mysqli_fetch_assoc($res_income)['total'] ?? 0;

$total_revenue = $order_sales + $finance_income;

// 3. ดึงรายการออเดอร์ทั้งหมดเพื่อแสดงในตาราง (ไม่รวมที่ยกเลิก)
$sql_list = "SELECT * FROM tb_orders WHERE order_status != 'ยกเลิกแล้ว' ORDER BY order_date DESC";
$result_list = mysqli_query($conn, $sql_list);
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>รายงานการเงิน & สถิติ - TatoFun Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f4f7f6; font-family: 'Kanit', sans-serif; }
        .card { border: none; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); }
        .stat-card { color: white; transition: 0.3s; overflow: hidden; }
        .stat-card:hover { transform: translateY(-5px); }
        .bg-gradient-primary { background: linear-gradient(135deg, #4e73df, #224abe); }
        .bg-gradient-success { background: linear-gradient(135deg, #1cc88a, #13855c); }
        .table-card { border-radius: 20px; background: white; }
        .btn-view-slip { background: #f8f9fc; border: 1px solid #eaecf4; color: #4e73df; transition: 0.2s; }
        .btn-view-slip:hover { background: #4e73df; color: white; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-0">รายงาน<span class="text-primary">การเงิน</span></h2>
            <p class="text-muted">ตรวจสอบรายได้ สถิติ และหลักฐานการโอนเงิน</p>
        </div>
        <div class="d-flex gap-2">
            <a href="manage_orders.php" class="btn btn-white border shadow-sm rounded-pill px-4">
                <i class="bi bi-cart3 me-2"></i>จัดการออเดอร์
            </a>
            <a href="index_ad.php" class="btn btn-dark shadow-sm rounded-pill px-4">
                <i class="bi bi-house me-2"></i>หน้าหลัก
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card stat-card bg-gradient-primary p-4 h-100 shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="opacity-75">ยอดขายจากออเดอร์ (สำเร็จ)</small>
                        <h2 class="fw-bold mb-0">฿ <?= number_format($order_sales, 2) ?></h2>
                    </div>
                    <i class="bi bi-bag-check fs-1 opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card bg-gradient-success p-4 h-100 shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="opacity-75">รวมรายรับสุทธิ</small>
                        <h2 class="fw-bold mb-0">฿ <?= number_format($total_revenue, 2) ?></h2>
                    </div>
                    <i class="bi bi-currency-dollar fs-1 opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card p-4 h-100 shadow-sm">
                <h5 class="fw-bold mb-4 text-center">สัดส่วนรายได้</h5>
                <div style="max-width: 250px; margin: auto;">
                    <canvas id="incomeChart"></canvas>
                </div>
                <div class="mt-4 small">
                    <div class="d-flex justify-content-between mb-1">
                        <span><i class="bi bi-circle-fill text-primary me-2"></i>ออเดอร์:</span>
                        <span class="fw-bold"><?= number_format(($order_sales/$total_revenue)*100, 1) ?>%</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><i class="bi bi-circle-fill text-success me-2"></i>ทั่วไป:</span>
                        <span class="fw-bold"><?= number_format(($finance_income/$total_revenue)*100, 1) ?>%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card table-card shadow-sm h-100">
                <div class="p-4 border-bottom bg-light rounded-top-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-list-ul me-2"></i>รายการตรวจสอบล่าสุด</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-white">
                            <tr>
                                <th class="ps-4">ออเดอร์</th>
                                <th>ลูกค้า</th>
                                <th class="text-end">ยอดเงิน</th>
                                <th class="text-center">ตรวจสอบ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result_list)): ?>
                            <tr>
                                <td class="ps-4 fw-bold">#<?= $row['order_id'] ?></td>
                                <td>
                                    <div class="fw-semibold small"><?= htmlspecialchars($row['cus_name']) ?></div>
                                    <div class="text-muted" style="font-size: 0.7rem;"><?= date('d/m/Y H:i', strtotime($row['order_date'])) ?></div>
                                </td>
                                <td class="text-end fw-bold text-dark">฿<?= number_format($row['total_price'], 2) ?></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-view-slip rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#slipModal<?= $row['order_id'] ?>">
                                        <i class="bi bi-image"></i> สลิป
                                    </button>

                                    <div class="modal fade" id="slipModal<?= $row['order_id'] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header border-0 pb-0">
                                                    <h6 class="modal-title fw-bold">สลิป #<?= $row['order_id'] ?></h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php if(!empty($row['slip_img'])): ?>
                                                        <img src="../img_slip/<?= $row['slip_img'] ?>" class="img-fluid rounded-3 shadow-sm">
                                                    <?php else: ?>
                                                        <div class="text-center py-4 text-muted small">
                                                            <i class="bi bi-exclamation-circle d-block fs-2 mb-2"></i>ไม่พบหลักฐาน
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <a href="order_detail.php?id=<?= $row['order_id'] ?>" class="btn btn-primary btn-sm w-100 rounded-pill">ดูข้อมูลเต็ม</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
// Chart.js - รายรับสัดส่วน
const ctx = document.getElementById('incomeChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['ออเดอร์', 'ทั่วไป'],
        datasets: [{
            data: [<?= $order_sales ?>, <?= $finance_income ?>],
            backgroundColor: ['#4e73df', '#1cc88a'],
            hoverOffset: 10,
            borderWidth: 0
        }]
    },
    options: {
        plugins: {
            legend: { display: false }
        },
        cutout: '70%'
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>