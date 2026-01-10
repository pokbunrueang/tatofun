<?php
session_start();
include '../config.php'; // ถอยออกไป 1 ชั้นเพื่อหาไฟล์ config

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // ลบข้อมูลในตาราง tb_finance โดยอ้างอิงจาก fin_id
    $sql = "DELETE FROM tb_finance WHERE fin_id = '$id'";
    
    if(mysqli_query($conn, $sql)) {
        // ลบสำเร็จ ให้เด้งกลับไปหน้าสรุปยอดขายทันที
        echo "<script>alert('ลบรายการเรียบร้อยแล้ว'); window.location='sales_report.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
    }
} else {
    header("Location: sales_report.php");
}
?>