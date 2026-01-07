<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['img_menu'])) {
    $id_menu = $_POST['id_menu'];
    
    // ตั้งชื่อไฟล์ใหม่เพื่อป้องกันชื่อซ้ำ
    $ext = pathinfo($_FILES['img_menu']['name'], PATHINFO_EXTENSION);
    $new_name = "menu_" . $id_menu . "_" . time() . "." . $ext;
    $target = "img_ad/" . $new_name;

    if (move_uploaded_file($_FILES['img_menu']['tmp_name'], $target)) {
        // อัปเดตชื่อไฟล์ในฐานข้อมูล
        $sql = "UPDATE tb_menu SET img_menu = '$new_name' WHERE id_menu = '$id_menu'";
        mysqli_query($conn, $sql);
        
        header("Location: manage_menu.php");
    } else {
        echo "<script>alert('อัปโหลดล้มเหลว!'); window.history.back();</script>";
    }
}
?>