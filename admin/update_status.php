<?php
session_start();
include '../config.php'; // เชื่อมต่อฐานข้อมูล

// ✅ 1. ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// ✅ 2. รับค่าจาก URL และแก้ไขคำผิด (mysqli_real_escape_string)
if (isset($_GET['id']) && isset($_GET['status'])) {
    // แก้ไขจาก escape_with_string เป็น escape_string
    $order_id = mysqli_real_escape_string($conn, $_GET['id']); 
    $new_status = mysqli_real_escape_string($conn, $_GET['status']);

    // ✅ 3. อัปเดตสถานะ
    $sql = "UPDATE tb_orders SET status = '$new_status' WHERE order_id = '$order_id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: manage_orders.php?msg=success");
    } else {
        header("Location: manage_orders.php?msg=error");
    }
} else {
    header("Location: manage_orders.php");
}
exit();
?>