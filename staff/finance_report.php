<?php
session_start();
include '../config.php';

// ดึงข้อมูลสรุปจาก tb_finance ตามโครงสร้างจริงในฐานข้อมูล
$sql_income = "SELECT SUM(fin_amount) as total FROM tb_finance WHERE fin_type = 'income'";
$res_income = mysqli_query($conn, $sql_income);
$income = mysqli_fetch_assoc($res_income)['total'] ?? 0;

$sql_expense = "SELECT SUM(fin_amount) as total FROM tb_finance WHERE fin_type = 'expense'";
$res_expense = mysqli_query($conn, $sql_expense);
$expense = mysqli_fetch_assoc($res_expense)['total'] ?? 0;

$profit = $income - $expense;
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>สรุปยอดขาย - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8f9fa; font-family: 'Kanit', sans-serif; }
        .card { border: none; border-radius: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .stat-card { border: none; border-radius: 20px; transition: 0.3s; color: white; }
        .stat-card:hover { transform: translateY(-5px); }
        .bg-gradient-green { background: linear-gradient(135deg, #28a745, #20c997); }
        .bg-gradient-red { background: linear-gradient(135deg, #dc3545, #f86d7d); }
        .bg-gradient-blue { background: linear-gradient(135deg, #007bff, #6610f2); }
    </style>
</head>
<body>

<div class="container-fluid bg-white py-3 shadow-sm mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="index_st.php" class="btn btn-light rounded-pill px-3 shadow-sm border-0" style="background-color: #f8f9fa;">
            <i class="bi bi-arrow-left-circle me-1"></i> กลับหน้าหลัก
        </a>
        
        <h4 class="fw-bold mb-0" style="color: #ffc107;">สรุปรายงานการเงิน</h4>
        
        <button class="btn btn-outline-success rounded-pill px-3 border-0" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> พิมพ์
        </button>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card stat-card bg-gradient-green p-4 h-100">
                <small class="opacity-75">รายรับทั้งหมด</small>
                <h2 class="fw-bold mb-0">฿ <?= number_format($income, 2) ?></h2>
                <i class="bi bi-graph-up-arrow position-absolute end-0 bottom-0 m-3 opacity-25 fs-1"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-gradient-red p-4 h-100">
                <small class="opacity-75">รายจ่ายทั้งหมด</small>
                <h2 class="fw-bold mb-0">฿ <?= number_format($expense, 2) ?></h2>
                <i class="bi bi-graph-down-arrow position-absolute end-0 bottom-0 m-3 opacity-25 fs-1"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-gradient-blue p-4 h-100">
                <small class="opacity-75">กำไรสุทธิ</small>
                <h2 class="fw-bold mb-0">฿ <?= number_format($profit, 2) ?></h2>
                <i class="bi bi-cash-coin position-absolute end-0 bottom-0 m-3 opacity-25 fs-1"></i>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card p-4 h-100 text-center">
                <h5 class="fw-bold mb-4 text-start"><i class="bi bi-pie-chart-fill text-warning me-2"></i>สัดส่วนการเงิน</h5>
                <canvas id="pieChart" style="max-height: 280px;"></canvas>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card p-4 h-100">
                <h5 class="fw-bold mb-4"><i class="bi bi-clock-history text-warning me-2"></i>รายการล่าสุด</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ประเภท</th>
                                <th>รายการ</th>
                                <th class="text-end">จำนวนเงิน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sql_list = "SELECT * FROM tb_finance ORDER BY fin_date DESC LIMIT 5";
                            $res_list = mysqli_query($conn, $sql_list);
                            while($row = mysqli_fetch_assoc($res_list)):
                            ?>
                            <tr>
                                <td>
                                    <span class="badge rounded-pill <?= $row['fin_type'] == 'income' ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $row['fin_type'] == 'income' ? 'รายรับ' : 'รายจ่าย' ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($row['fin_title']) ?: 'ไม่ระบุรายการ' ?></td>
                                <td class="text-end fw-bold">฿ <?= number_format($row['fin_amount'], 2) ?></td>
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
const ctx = document.getElementById('pieChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['รายรับ', 'รายจ่าย'],
        datasets: [{
            data: [<?= $income ?>, <?= $expense ?>],
            backgroundColor: ['#28a745', '#dc3545'],
            borderWidth: 5,
            borderColor: '#ffffff'
        }]
    },
    options: {
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>

</body>
</html>