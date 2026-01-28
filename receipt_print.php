<?php 
session_start();
include 'config.php'; // ไฟล์อยู่ระดับเดียวกัน ไม่ต้องมี ../

// รับ order_id และตรวจสอบความปลอดภัย
$order_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if (empty($order_id)) {
    die("ไม่พบรหัสคำสั่งซื้อ");
}

// 1. ดึงข้อมูลหัวออเดอร์
$sql_order = "SELECT * FROM tb_orders WHERE order_id = '$order_id'";
$res_order = mysqli_query($conn, $sql_order);
$order = mysqli_fetch_assoc($res_order);

if (!$order) {
    die("ไม่พบข้อมูลการสั่งซื้อ");
}

// 2. ดึงรายการอาหาร
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
    <title>ใบเสร็จรับเงิน #<?php echo $order['order_id']; ?></title>
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; }
        }
        body { font-family: 'Tahoma', sans-serif; font-size: 14px; background: #f5f5f5; }
        #receipt { 
            width: 350px; 
            margin: 20px auto; 
            padding: 20px; 
            background: white; 
            border: 1px dashed #ccc;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header { text-align: center; border-bottom: 1px dashed #eee; padding-bottom: 10px; margin-bottom: 10px; }
        .item-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .item-table th { text-align: left; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #666; }
        .btn-print { 
            display: block; width: 350px; margin: 10px auto; padding: 10px;
            background: #007bff; color: white; text-align: center; 
            text-decoration: none; border-radius: 5px;
        }
    </style>
</head>
<body>

    <a href="#" class="btn-print no-print" onclick="window.print();">พิมพ์ใบเสร็จ (Print)</a>

    <div id="receipt">
        <div class="header">
            <h2 style="margin: 0;">TatoFun Fries</h2>
            <p style="margin: 5px 0;">ขอบคุณที่ใช้บริการ</p>
        </div>

        <p><strong>เลขที่:</strong> #<?php echo $order['order_id']; ?></p>
        <p><strong>วันที่:</strong> <?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></p>
        <p><strong>ลูกค้า:</strong> <?php echo htmlspecialchars($order['cus_name']); ?></p>
        <hr style="border: 0; border-top: 1px dashed #eee;">

        <table class="item-table">
            <thead>
                <tr>
                    <th>รายการ</th>
                    <th class="text-right">จำนวน</th>
                    <th class="text-right">เงิน</th>
                </tr>
            </thead>
            <tbody>
                <?php while($item = mysqli_fetch_assoc($res_items)): ?>
                <tr>
                    <td><?php echo $item['name_menu']; ?></td>
                    <td class="text-right"><?php echo $item['qty']; ?></td>
                    <td class="text-right"><?php echo number_format($item['price'] * $item['qty'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <hr style="border: 0; border-top: 1px dashed #eee;">
        
        <table style="width: 100%; font-weight: bold;">
            <tr>
                <td>ยอดรวมทั้งสิ้น</td>
                <td class="text-right">฿<?php echo number_format($order['total_price'], 2); ?></td>
            </tr>
        </table>

        <div class="footer">
            <p>สถานะ: <?php echo $order['order_status']; ?></p>
            <p>*** สินค้าซื้อแล้วไม่รับเปลี่ยนคืน ***</p>
        </div>
    </div>

</body>
</html>