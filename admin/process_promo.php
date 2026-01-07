<!--เสร็จแล้ว-->
<?php
session_start();
include '../config.php'; 

// 1. ตรวจสอบการ Login ก่อนทำอะไรทั้งสิ้น


if (isset($_POST['save_promo'])) {
    // 2. รับค่าและทำความสะอาดข้อมูล
    $p_name   = mysqli_real_escape_string($conn, $_POST['p_name']);
    $p_detail = mysqli_real_escape_string($conn, $_POST['p_detail']);
    $p_status = isset($_POST['p_status']) ? mysqli_real_escape_string($conn, $_POST['p_status']) : 'on';

    // 3. ตรวจสอบว่าเลือกไฟล์มาจริงไหม และไม่มี Error จากเบราว์เซอร์
    if (isset($_FILES['p_img']) && $_FILES['p_img']['error'] === 0) {
        
        $filename = $_FILES['p_img']['name'];
        $tmp_name = $_FILES['p_img']['tmp_name'];
        
        // ตรวจสอบนามสกุลไฟล์
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed_types = array('jpg', 'jpeg', 'png', 'webp');

        if (in_array($ext, $allowed_types)) {
            
            // สุ่มชื่อไฟล์ใหม่ป้องกันชื่อซ้ำ
            $new_name = "promo_" . time() . "_" . rand(100, 999) . "." . $ext; 
            
            // กำหนด Path - แนะนำให้ใช้ __DIR__ เพื่อป้องกัน Path หาย
            $target_dir = __DIR__ . "/uploads/promotions/";
            $target_file = $target_dir . $new_name;

            // ตรวจสอบและสร้างโฟลเดอร์ถ้ายังไม่มี
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // 4. พยายามย้ายไฟล์
            if (move_uploaded_file($tmp_name, $target_file)) {
                
                // 5. บันทึกลงฐานข้อมูล (ตรวจสอบชื่อคอลัมน์ให้ตรงกับ table ของคุณ)
                $sql = "INSERT INTO tb_promotions (pro_name, pro_detail, pro_img, pro_status) 
                        VALUES ('$p_name', '$p_detail', '$new_name', '$p_status')";
                
                if (mysqli_query($conn, $sql)) {
                    echo "<script>
                            alert('เพิ่มโปรโมชั่นเรียบร้อยแล้ว');
                            window.location='manage_promotion.php';
                          </script>";
                } else {
                    // ถ้า DB พัง ให้ลบรูปที่เพิ่งอัปโหลดทิ้งด้วยเพื่อไม่ให้รก
                    unlink($target_file);
                    echo "Database Error: " . mysqli_error($conn);
                }
            } else {
                echo "<script>alert('Error: ไม่สามารถย้ายไฟล์เข้าโฟลเดอร์ได้ ตรวจสอบ Permission โฟลเดอร์ uploads'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('ผิดพลาด: อนุญาตเฉพาะไฟล์ .jpg, .jpeg, .png, .webp เท่านั้น'); window.history.back();</script>";
        }
    } else {
        // แจ้ง Error ตามจริงที่เกิดจากระบบอัปโหลดไฟล์
        $error_code = $_FILES['p_img']['error'];
        echo "<script>alert('กรุณาเลือกรูปภาพ (Error Code: $error_code)'); window.history.back();</script>";
    }
} else {
    header("Location: manage_promotion.php");
    exit();
}
?>