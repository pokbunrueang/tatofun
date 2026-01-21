<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $amount = $_POST['amount'];
    $pay_date = $_POST['pay_date'];
    $ship_name = $_POST['ship_name'];
    $ship_phone = $_POST['ship_phone'];
    $ship_address = $_POST['ship_address'];

    // จัดการอัปโหลดไฟล์รูปสลิป
    $ext = pathinfo(basename($_FILES['slip']['name']), PATHINFO_EXTENSION);
    $new_name = "slip_" . $order_id . "_" . time() . "." . $ext;
    $target_path = "admin/img_slip/" . $new_name;

    if (move_uploaded_file($_FILES['slip']['tmp_name'], $target_path)) {
        
        // 1. บันทึกข้อมูลการเงิน
        $sql_pay = "INSERT INTO tb_payment (order_id, pay_amount, pay_date, pay_slip) 
                    VALUES ('$order_id', '$amount', '$pay_date', '$new_name')";
        mysqli_query($conn, $sql_pay);

        // 2. บันทึกที่อยู่จัดส่ง
        $sql_ship = "INSERT INTO tb_shipping (order_id, ship_name, ship_phone, ship_address) 
                     VALUES ('$order_id', '$ship_name', '$ship_phone', '$ship_address')";
        mysqli_query($conn, $sql_ship);

        // 3. อัปเดตสถานะออเดอร์เป็น 'รอตรวจสอบ'
        $sql_update = "UPDATE tb_order SET order_status = 'waiting_verify' WHERE order_id = '$order_id'";
        mysqli_query($conn, $sql_update);

        echo "<script>alert('แจ้งชำระเงินเรียบร้อย! รอแอดมินตรวจสอบ'); window.location='index.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ";
    }
}
?>