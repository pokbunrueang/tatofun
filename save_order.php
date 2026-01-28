<?php
session_start();
include 'config.php';

// 1. รับค่าจากฟอร์ม
$cus_name = mysqli_real_escape_string($conn, $_POST['cus_name']);
$cus_tel = mysqli_real_escape_string($conn, $_POST['cus_tel']);
$cus_address = mysqli_real_escape_string($conn, $_POST['cus_address']);
$payment = mysqli_real_escape_string($conn, $_POST['payment']);

// ตรวจสอบ user_id ถ้าไม่ได้ Login ให้เป็น NULL (ไม่มีเครื่องหมายคำพูด)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NULL'; 
$order_date = date("Y-m-d H:i:s");

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart_items)) {
    header("Location: menu.php");
    exit();
}

// 2. คำนวณยอดรวมจาก tb_menu
$total_price = 0;
foreach ($cart_items as $id => $qty) {
    $id_safe = mysqli_real_escape_string($conn, $id);
    $sql_p = "SELECT price_menu FROM tb_menu WHERE id_menu = '$id_safe'";
    $res_p = mysqli_query($conn, $sql_p);
    $row_p = mysqli_fetch_assoc($res_p);
    $total_price += ($row_p['price_menu'] * $qty);
}

// 3. บันทึกลงตาราง tb_orders
// ปรับปรุง: ใช้ 'รอชำระเงิน' เป็นสถานะเริ่มต้นเพื่อให้ Flow สอดคล้องกับการอัปโหลดสลิป
$sql_order = "INSERT INTO tb_orders (user_id, cus_name, cus_tel, cus_address, total_price, payment, order_status, order_date) 
              VALUES ($user_id, '$cus_name', '$cus_tel', '$cus_address', '$total_price', '$payment', 'รอชำระเงิน', '$order_date')";

if (mysqli_query($conn, $sql_order)) {
    $order_id = mysqli_insert_id($conn); // ดึงเลข Order ID ที่เพิ่งสร้าง

    // 4. บันทึกรายละเอียดลงตาราง tb_order_details
    foreach ($cart_items as $id => $qty) {
        $id_safe = mysqli_real_escape_string($conn, $id);
        $sql_menu = "SELECT price_menu FROM tb_menu WHERE id_menu = '$id_safe'";
        $res_menu = mysqli_query($conn, $sql_menu);
        $row_menu = mysqli_fetch_assoc($res_menu);
        $price = $row_menu['price_menu'];

        $sql_detail = "INSERT INTO tb_order_details (order_id, id_menu, qty, price) 
                       VALUES ('$order_id', '$id_safe', '$qty', '$price')";
        mysqli_query($conn, $sql_detail);
    }

    // 5. ล้างตะกร้าสินค้า และเด้งไปหน้าชำระเงิน (QR Code) พร้อมส่ง ID ไปด้วย
    unset($_SESSION['cart']);
    echo "<script>
            alert('บันทึกการสั่งซื้อเรียบร้อยแล้ว!');
            window.location.href='checkout_payment.php?order_id=$order_id';
          </script>";
} else {
    echo "เกิดข้อผิดพลาดในการบันทึกออเดอร์: " . mysqli_error($conn);
}
?>