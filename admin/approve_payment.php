<?php
session_start();
include '../config.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $order_id = $_GET['id'];
    $status = $_GET['status'];

    if ($status == 'approve') {
        // --- ส่วนที่ 1: อัปเดตสถานะการชำระเงิน ---
        $sql1 = "UPDATE tb_order SET order_status = 'paid' WHERE order_id = '$order_id'";
        mysqli_query($conn, $sql1);

        $sql2 = "UPDATE tb_payment SET pay_status = 'verified' WHERE order_id = '$order_id'";
        mysqli_query($conn, $sql2);
        
        // --- ส่วนที่ 2: ระบบตัดสต็อกสินค้าอัตโนมัติ (เพิ่มใหม่) ---
        // 1. ดึงรายการสินค้าและจำนวนจากออเดอร์นี้
        // สมมติตารางรายละเอียดออเดอร์คุณชื่อ tb_order_detail และมีคอลัมน์ menu_id, qty
        $sql_items = "SELECT menu_id, qty FROM tb_order_detail WHERE order_id = '$order_id'";
        $res_items = mysqli_query($conn, $sql_items);

        while($item = mysqli_fetch_assoc($res_items)) {
            $m_id = $item['menu_id'];
            $qty = $item['qty'];

            // 2. อัปเดตลดจำนวนสต็อกในตาราง tb_menu
            $sql_update_stock = "UPDATE tb_menu 
                                 SET menu_stock = menu_stock - $qty 
                                 WHERE id_menu = '$m_id'";
            mysqli_query($conn, $sql_update_stock);
        }

        // --- ส่วนที่ 3: บันทึกลงตารางการเงิน (tb_finance) ---
        $res_pay = mysqli_query($conn, "SELECT pay_amount FROM tb_payment WHERE order_id = '$order_id'");
        $pay_data = mysqli_fetch_assoc($res_pay);
        $amount = $pay_data['pay_amount'];
        
        $sql3 = "INSERT INTO tb_finance (order_id, amount, date_added) VALUES ('$order_id', '$amount', NOW())";
        mysqli_query($conn, $sql3);

    } else {
        // กรณีปฏิเสธการชำระเงิน
        $sql_reject = "UPDATE tb_order SET order_status = 'pending' WHERE order_id = '$order_id'";
        mysqli_query($conn, $sql_reject);
    }

    header("Location: manage_payment.php");
    exit();
}
?>