<?php
session_start();
include 'config.php';

// รับค่าจากฟอร์ม
$cus_name = mysqli_real_escape_string($conn, $_POST['cus_name']);
$cus_tel = mysqli_real_escape_string($conn, $_POST['cus_tel']);
$cus_address = mysqli_real_escape_string($conn, $_POST['cus_address']);
$payment = mysqli_real_escape_string($conn, $_POST['payment']);
$order_date = date("Y-m-d H:i:s");

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// คำนวณยอดรวม
$total_price = 0;
foreach ($cart_items as $id => $qty) {
    $id = mysqli_real_escape_string($conn, $id);
    $sql_p = "SELECT price_menu FROM tb_menu WHERE id_menu = '$id'";
    $res_p = mysqli_query($conn, $sql_p);
    $row_p = mysqli_fetch_assoc($res_p);
    $total_price += ($row_p['price_menu'] * $qty);
}

// แก้ไขชื่อตารางให้มีตัว s ตามฐานข้อมูลของคุณ
$sql_order = "INSERT INTO tb_orders (cus_name, cus_tel, cus_address, total_price, payment, order_status, order_date) 
              VALUES ('$cus_name', '$cus_tel', '$cus_address', '$total_price', '$payment', 'รอตรวจสอบ', '$order_date')";

if (mysqli_query($conn, $sql_order)) {
    $order_id = mysqli_insert_id($conn);

    foreach ($cart_items as $id => $qty) {
        $id = mysqli_real_escape_string($conn, $id);
        $sql_menu = "SELECT price_menu FROM tb_menu WHERE id_menu = '$id'";
        $res_menu = mysqli_query($conn, $sql_menu);
        $row_menu = mysqli_fetch_assoc($res_menu);
        $price = $row_menu['price_menu'];

        // แก้ไขชื่อตารางให้มีตัว s ตรงนี้ด้วยครับ
        $sql_detail = "INSERT INTO tb_order_details (order_id, id_menu, qty, price) 
                       VALUES ('$order_id', '$id', '$qty', '$price')";
        mysqli_query($conn, $sql_detail);
    }

    unset($_SESSION['cart']);
    echo "<script>
            alert('บันทึกการสั่งซื้อเรียบร้อยแล้ว!');
            window.location.href='index.php';
          </script>";
} else {
    echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
}
?>