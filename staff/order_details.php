<?php
session_start();
include '../config.php'; 

// 1. ตรวจสอบสิทธิ์ (ถ้าเป็นลูกค้าให้เช็ค session แบบลูกค้า ถ้าเป็น staff ให้เช็คแบบ staff)
// ในที่นี้คงไว้ตามที่คุณส่งมาคือเช็ค staff
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit();
}

// 2. ตรวจสอบ ID ออเดอร์
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('กรุณาเลือกออเดอร์ก่อน'); window.location='manage_orders.php';</script>";
    exit();
}

$order_id = mysqli_real_escape_string($conn, $_GET['id']);

// 3. ดึงข้อมูลหัวข้อออเดอร์ (ข้อมูลลูกค้า + ยอดรวม)
$sql_order = "SELECT * FROM tb_orders WHERE order_id = '$order_id'";
$res_order = mysqli_query($conn, $sql_order);
$order = mysqli_fetch_assoc($res_order);

if (!$order) {
    echo "<script>alert('ไม่พบข้อมูลออเดอร์นี้'); window.location='manage_orders.php';</script>";
    exit();
}

// 4. ดึงข้อมูลรายการอาหารภายในออเดอร์
$sql_items = "SELECT od.*, m.name_menu 
              FROM tb_order_details od
              JOIN tb_menu m ON od.id_menu = m.id_menu 
              WHERE od.order_id = '$order_id'";
$res_items = mysqli_query($conn, $sql_items);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดออเดอร์ #<?php echo $order_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --tato-yellow: #ffca28;
            --tato-orange: #f57c00;
            --tato-dark: #2d3436;
            --tato-bg: #f8f9fa;
        }

        body { 
            font-family: 'Kanit', sans-serif; 
            background: var(--tato-bg); 
            min-height: 100vh; 
        }

        .receipt-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
        }

        .receipt-card { 
            border: none; 
            border-radius: 30px; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.05); 
            background: #fff; 
            max-width: 500px;
            width: 100%;
            position: relative;
        }

        .receipt-header { 
            background: linear-gradient(135deg, var(--tato-yellow), var(--tato-orange)); 
            padding: 30px; 
            text-align: center; 
            color: #000;
            border-radius: 30px 30px 0 0;
        }

        .total-box { 
            background: #fff9e6; 
            border-radius: 20px; 
            padding: 20px; 
            border: 2px dashed var(--tato-yellow); 
            margin-top: 20px;
        }

        .payment-qr {
            text-align: center;
            background: #fdfdfd;
            border-radius: 20px;
            padding: 20px;
            margin-top: 20px;
            border: 1px solid #eee;
        }

        .btn-action {
            border-radius: 50px;
            padding: 12px;
            width: 100%;
            display: block;
            text-align: center;
            font-weight: 600;
            text-decoration: none;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-back { background: var(--tato-dark); color: #fff; }
        .btn-print { border: 2px solid var(--tato-orange); color: var(--tato-orange); }
        .btn-print:hover { background: var(--tato-orange); color: #fff; }

        @media print {
            .container-fluid, .btn-action { display: none !important; }
            body { background: #fff; }
            .receipt-wrapper { padding: 0; }
            .receipt-card { box-shadow: none; border: 1px solid #ddd; max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="container-fluid bg-white py-3 shadow-sm mb-2">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="manage_orders.php" class="btn btn-light rounded-pill px-3 border-0">
            <i class="bi bi-arrow-left-circle me-1"></i> กลับไปรายการออเดอร์
        </a>
        <h4 class="fw-bold mb-0 text-warning">TatoFun Detail</h4>
    </div>
</div>

<div class="container receipt-wrapper">
    <div class="receipt-card">
        <div class="receipt-header">
            <i class="bi bi-receipt-cutoff fs-1"></i>
            <h3 class="fw-bold mb-0">TatoFun Fries</h3>
            <p class="mb-0 small">ใบแจ้งรายการและหลักฐานการสั่งซื้อ</p>
        </div>

        <div class="card-body p-4">
            <div class="mb-4 border-bottom pb-3">
                <div class="row">
                    <div class="col-6">
                        <span class="text-muted small">หมายเลขคำสั่งซื้อ:</span>
                        <div class="fw-bold text-dark">#<?php echo $order['order_id']; ?></div>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-muted small">สถานะ:</span>
                        <div class="badge <?php echo ($order['order_status'] == 'รอตรวจสอบ') ? 'bg-warning' : 'bg-success'; ?> d-block">
                            <?php echo $order['order_status']; ?>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-muted small">ข้อมูลลูกค้า:</span>
                    <div class="small fw-bold"><?php echo htmlspecialchars($order['cus_name']); ?> (<?php echo $order['cus_tel']; ?>)</div>
                    <div class="small text-muted text-truncate"><?php echo htmlspecialchars($order['cus_address']); ?></div>
                </div>
            </div>

            <table class="table table-borderless">
                <thead>
                    <tr class="border-bottom small text-muted">
                        <th>รายการ</th>
                        <th class="text-center">จำนวน</th>
                        <th class="text-end">ราคา</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($item = mysqli_fetch_assoc($res_items)): ?>
                    <tr>
                        <td class="py-2 small">
                            <?php echo htmlspecialchars($item['name_menu']); ?>
                        </td>
                        <td class="text-center py-2"><?php echo $item['qty']; ?></td>
                        <td class="text-end py-2 fw-bold">฿<?php echo number_format($item['price'] * $item['qty'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="total-box">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">ยอดรวมสุทธิ</span>
                    <span class="h3 fw-bold text-danger mb-0">฿<?php echo number_format($order['total_price'], 2); ?></span>
                </div>
            </div>

            <?php if($order['order_status'] == 'รอตรวจสอบ'): ?>
            <div class="payment-qr">
                <p class="mb-2 fw-bold"><i class="bi bi-qr-code-scan me-2"></i>สแกนเพื่อชำระเงิน (PromptPay)</p>
                <?php
                $promptpay_id = "0812345678"; // ** แก้เป็นเบอร์โทรของคุณ **
                $amount = $order['total_price'];
                $qr_url = "https://promptpay.io/$promptpay_id/$amount.png";
                ?>
                <img src="<?php echo $qr_url; ?>" style="width:200px;" class="img-fluid rounded shadow-sm">
                <p class="mt-2 mb-0 x-small text-muted">ชื่อบัญชี: ร้าน TatoFun Fries</p>
            </div>
            <?php endif; ?>

            <div class="mt-4">
                <button onclick="window.print()" class="btn-action btn-print">
                    <i class="bi bi-printer me-2"></i> พิมพ์ใบแจ้งหนี้/ใบเสร็จ
                </button>
                <a href="manage_orders.php" class="btn-action btn-back">
                    กลับไปหน้าจัดการ
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>