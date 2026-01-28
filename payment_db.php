<?php
session_start();
include 'config.php';

$target_dir = "admin/img_slip/"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);

    // ตรวจสอบชื่อ input: โค้ดจะเช็คทั้ง 'slip' และ 'pay_slip' 
    // เพื่อป้องกันปัญหาชื่อในฟอร์มกับโค้ดไม่ตรงกัน
    $file_field = '';
    if (isset($_FILES['slip'])) { $file_field = 'slip'; }
    elseif (isset($_FILES['pay_slip'])) { $file_field = 'pay_slip'; }

    if ($file_field !== '' && $_FILES[$file_field]['error'] == 0) {
        
        $extension = pathinfo($_FILES[$file_field]['name'], PATHINFO_EXTENSION);
        $new_name = "slip_" . $order_id . "_" . time() . "." . $extension;
        $target_path = $target_dir . $new_name;

        if (move_uploaded_file($_FILES[$file_field]['tmp_name'], $target_path)) {
            
            // อัปเดตตาราง tb_orders (มี s) ตามโครงสร้างจริงของคุณ
            $sql = "UPDATE tb_orders SET 
                    order_status = 'รอตรวจสอบชำระเงิน', 
                    slip_img = '$new_name' 
                    WHERE order_id = '$order_id'";
            
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('แจ้งชำระเงินเรียบร้อยแล้ว!'); window.location='my_orders.php';</script>";
            } else {
                echo "ฐานข้อมูลผิดพลาด: " . mysqli_error($conn);
            }
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการย้ายไฟล์ไปยัง $target_path'); window.history.back();</script>";
        }
    } else {
        // ส่วนนี้จะบอกเราชัดเจนว่าทำไมถึงไม่เห็นไฟล์
        $debug_info = "ไม่พบชื่อ input ที่ถูกต้อง (ต้องชื่อ slip หรือ pay_slip)";
        if ($file_field !== '') { $debug_info = "ไฟล์มี Error Code: " . $_FILES[$file_field]['error']; }
        
        echo "<script>alert('กรุณาเลือกไฟล์สลิป ($debug_info)'); window.history.back();</script>";
    }
}
?>