<?php
session_start();
include '../config.php'; // ตรวจสอบว่า Path ถูกต้องตามโครงสร้างโฟลเดอร์ของคุณ

if (isset($_GET['id']) && isset($_GET['status'])) {
    $order_id = mysqli_real_escape_string($conn, $_GET['id']);
    $new_status = mysqli_real_escape_string($conn, $_GET['status']);

    // แก้ไขจาก status เป็น order_status ให้ตรงกับฐานข้อมูล
    $sql = "UPDATE tb_orders SET order_status = '$new_status' WHERE order_id = '$order_id'";

    if (mysqli_query($conn, $sql)) {
        // อัปเดตสำเร็จ ส่งกลับหน้าจัดการพร้อมข้อความแจ้งเตือน
        header("Location: manage_orders.php?msg=updated");
        exit();
    } else {
        // หากเกิดข้อผิดพลาด จะแสดง Error จริงจาก MySQL ออกมา
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // หากเข้าหน้านี้โดยไม่มีค่า id หรือ status ส่งมา ให้ดีดกลับหน้าหลัก
    header("Location: manage_orders.php");
    exit();
}
?>