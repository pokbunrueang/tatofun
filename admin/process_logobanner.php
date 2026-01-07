<!--เสร็จแล้ว-->
<?php
session_start();
// เชื่อมต่อฐานข้อมูล
include '../config.php'; 

// --- ส่วนที่ 1: สำหรับการอัปเดต/แก้ไขรูปภาพ (ทำงานเมื่อกดปุ่มอัปเดต) ---
if (isset($_POST['btn_save'])) {
    $id = $_POST['id_lb'];
    $file = $_FILES['img_file'];

    if ($file['error'] == 0) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = array('jpg', 'jpeg', 'png', 'webp');

        if (in_array($ext, $allowed)) {
            
            // ดึงชื่อรูปเก่ามาเพื่อลบทิ้ง
            $old_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name_lb FROM tb_logobanner WHERE id_lb = '$id'"));
            $old_file = "img_ad/" . $old_data['name_lb'];
            if (!empty($old_data['name_lb']) && file_exists($old_file) && is_file($old_file)) {
                unlink($old_file); 
            }

            // ตั้งชื่อใหม่และกำหนดที่เก็บไฟล์
            $new_name = "lb_" . time() . "." . $ext;
            $target = "img_ad/" . $new_name;

            if (move_uploaded_file($file['tmp_name'], $target)) {
                // อัปเดตข้อมูลในฐานข้อมูล
                $sql = "UPDATE tb_logobanner SET name_lb = '$new_name' WHERE id_lb = '$id'";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('อัปเดตสำเร็จ!'); window.location='manage_logobanner.php';</script>";
                } else {
                    echo "DB Error: " . mysqli_error($conn);
                }
            } else {
                echo "ไม่สามารถย้ายไฟล์ไปโฟลเดอร์ img_ad ได้";
            }
        } else {
            echo "<script>alert('กรุณาอัปโหลดไฟล์รูปภาพเท่านั้น!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('กรุณาเลือกไฟล์รูปภาพก่อน!'); window.history.back();</script>";
    }
}

// --- ส่วนที่ 2: สำหรับการลบรูปภาพ (ทำงานเมื่อกดปุ่มถังขยะ) ---
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    
    // 1. ดึงชื่อไฟล์เดิมออกมาเพื่อลบไฟล์จริงในเครื่อง
    $res = mysqli_query($conn, "SELECT name_lb FROM tb_logobanner WHERE id_lb = '$id'");
    $data = mysqli_fetch_assoc($res);
    $old_file = "img_ad/" . $data['name_lb'];

    // ตรวจสอบว่ามีชื่อไฟล์ในฐานข้อมูลและมีไฟล์อยู่จริงไหม
    if (!empty($data['name_lb']) && file_exists($old_file) && is_file($old_file)) {
        unlink($old_file); // ลบไฟล์ออกจากโฟลเดอร์ img_ad
    }

    // 2. อัปเดตฐานข้อมูลให้ชื่อไฟล์เป็นค่าว่าง
    $sql = "UPDATE tb_logobanner SET name_lb = '' WHERE id_lb = '$id'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('ลบรูปภาพเรียบร้อยแล้ว'); window.location='manage_logobanner.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>