<?php
session_start();
include '../config.php'; // ตรวจสอบว่า Path ถูกต้องตามโครงสร้างโฟลเดอร์ admin

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $order_id = mysqli_real_escape_string($conn, $_GET['id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);

    // อัปเดตสถานะในตาราง tb_orders
    $sql = "UPDATE tb_orders SET order_status = '$status' WHERE order_id = '$order_id'";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('อัปเดตสถานะเป็น $status เรียบร้อยแล้ว'); 
                window.location='manage_orders.php';
              </script>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    header("Location: manage_orders.php");
    exit();
}
?>