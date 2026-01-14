<?php
session_start();
include '../config.php'; // ตรวจสอบว่า path ถูกต้องตามโครงสร้าง

// 1. ดึงข้อมูลเฉพาะ "รายรับ" ของ "วันนี้" เท่านั้น
$sql_income = "SELECT SUM(fin_amount) as total FROM tb_finance 
               WHERE fin_type = 'income' AND DATE(fin_date) = CURDATE()";
$res_income = mysqli_query($conn, $sql_income);
$income = mysqli_fetch_assoc($res_income)['total'] ?? 0;

// หมายเหตุ: ไม่ดึงรายจ่ายและกำไร เพื่อความเป็นส่วนตัวของข้อมูลร้าน
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>รายรับวันนี้ - TatoFun Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Kanit', sans-serif; }
        .card { border: none; border-radius: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .stat-card { border: none; border-radius: 20px; color: white; background: linear-gradient(135deg, #28a745, #20c997); }
    </style>
</head>
<body>

<div class="container-fluid bg-white py-3 shadow-sm mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="index_st.php" class="btn btn-light rounded-pill px-3">
            <i class="bi bi-arrow-left-circle me-1"></i> กลับหน้าหลัก
        </a>
        <h4 class="fw-bold mb-0" style="color: #ffc107;">สรุปรายได้วันนี้</h4>
        <div style="width: 100px;"></div> </div>
</div>

<div class="container pb-5">
    <div class="row g-4 mb-4 justify-content-center">
        <div class="col-md-6">
            <div class="card stat-card p-5 text-center position-relative overflow-hidden">
                <small class="opacity-75 d-block mb-2">ยอดรายรับวันนี้ (<?= date('d/m/Y') ?>)</small>
                <h1 class="display-4 fw-bold mb-0">฿ <?= number_format($income, 2) ?></h1>
                <i class="bi bi-cash-stack position-absolute end-0 bottom-0 m-3 opacity-25" style="font-size: 5rem;"></i>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <h5 class="fw-bold mb-4"><i class="bi bi-list-stars text-warning me-2"></i>รายการรายรับล่าสุดของวันนี้</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>เวลา</th>
                        <th>รายการ</th>
                        <th class="text-end">จำนวนเงิน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // ดึงเฉพาะรายรับของวันนี้มาโชว์ให้พนักงานตรวจสอบ
                    $sql_list = "SELECT * FROM tb_finance 
                                 WHERE fin_type = 'income' AND DATE(fin_date) = CURDATE() 
                                 ORDER BY fin_date DESC";
                    $res_list = mysqli_query($conn, $sql_list);
                    if(mysqli_num_rows($res_list) > 0):
                        while($row = mysqli_fetch_assoc($res_list)):
                    ?>
                    <tr>
                        <td class="text-muted"><?= date('H:i', strtotime($row['fin_date'])) ?> น.</td>
                        <td><?= htmlspecialchars($row['fin_title']) ?: 'ออเดอร์ร้านค้า' ?></td>
                        <td class="text-end fw-bold text-success">฿ <?= number_format($row['fin_amount'], 2) ?></td>
                    </tr>
                    <?php 
                        endwhile; 
                    else:
                        echo "<tr><td colspan='3' class='text-center py-4 text-muted'>ยังไม่มีรายรับในวันนี้</td></tr>";
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>