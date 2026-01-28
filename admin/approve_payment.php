<?php
session_start();
include '../config.php'; // เชื่อมต่อฐานข้อมูล

// 1. ตรวจสอบสิทธิ์ Admin เพื่อความปลอดภัย
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("คุณไม่มีสิทธิ์เข้าถึงส่วนนี้");
}

// 2. รับค่า ID ออเดอร์ และ สถานะที่ต้องการเปลี่ยน จาก URL
if (isset($_GET['id']) && isset($_GET['status'])) {
    
    $order_id = mysqli_real_escape_string($conn, $_GET['id']);
    $new_status = mysqli_real_escape_string($conn, $_GET['status']);

    // 3. อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE tb_orders SET order_status = '$new_status' WHERE order_id = '$order_id'";

    if (mysqli_query($conn, $sql)) {
        // อัปเดตสำเร็จ: ส่งกลับไปหน้าเดิมพร้อมแจ้งเตือน
        echo "<script>
                alert('อัปเดตสถานะออเดอร์ #$order_id เป็น $new_status เรียบร้อย!');
                window.location = 'manage_orders.php';
              </script>";
    } else {
        // เกิดข้อผิดพลาด
        echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
    }
} else {
    // ถ้าไม่มีการส่งค่ามา ให้เด้งกลับหน้าหลัก
    header("Location: manage_orders.php");
    exit();
}
?>