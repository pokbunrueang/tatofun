<?php
session_start();
include('../config.php'); 

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. ดึงชื่อไฟล์รูปภาพเดิมจากฐานข้อมูลก่อนลบ
    $check_sql = "SELECT img_menu FROM tb_menu WHERE id_menu = '$id'";
    $result = mysqli_query($conn, $check_sql);
    $row = mysqli_fetch_array($result);
    $file_name = $row['img_menu'];

    // 2. ลบไฟล์รูปภาพออกจากโฟลเดอร์ img_ad/ (ถ้ามี)
    if(!empty($file_name)) {
        $file_path = "img_ad/" . $file_name;
        if(file_exists($file_path)) {
            unlink($file_path); // สั่งลบไฟล์จริงออกจาก Server
        }
    }

    // 3. ลบข้อมูลออกจากฐานข้อมูล
    $delete_sql = "DELETE FROM tb_menu WHERE id_menu = '$id'";
    
    if(mysqli_query($conn, $delete_sql)) {
        header("Location: manage_menu.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>