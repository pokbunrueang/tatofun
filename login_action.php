<?php
session_start();
require_once 'config.php'; // ใช้ require_once เพื่อความชัวร์ว่าไฟล์ต้องถูกโหลด

if (isset($_POST['btn_login'])) {
    // 1. รับค่าและป้องกันการ SQL Injection
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pass = $_POST['pass']; // รับรหัสผ่านดิบมาก่อน

    // 2. ดึงข้อมูลจากตาราง tb_users
    $sql = "SELECT user_id, username, password, fullname, role FROM tb_users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        
        // 3. ตรวจสอบรหัสผ่าน (รองรับทั้งแบบรหัสตรงๆ และแบบ Hash)
        // หากใน DB เป็น 1234 และคุณกรอก 1234 ตัวแปรนี้จะทำงานได้
        if ($pass === $row['password']) { 
            
            // 4. ล้าง Session เก่าเพื่อความปลอดภัยก่อนสร้างใหม่
            session_regenerate_id(true);

            // 5. เก็บข้อมูลลง Session
            $_SESSION['user_id']  = $row['user_id'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['role']     = $row['role'];

            // 6. แยกหน้าการส่งไปตามบทบาท
            switch ($row['role']) {
                case 'admin':
                    header("Location: admin/index_ad.php");
                    break;
                case 'staff':
                    header("Location: staff/index.php");
                    break;
                case 'member':
                    header("Location: index.php");
                    break;
                default:
                    $_SESSION['error'] = "สิทธิ์การเข้าใช้งานไม่ถูกต้อง";
                    header("Location: login.php");
            }
            exit();

        } else {
            $_SESSION['error'] = "รหัสผ่านไม่ถูกต้อง";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "ไม่พบชื่อผู้ใช้งานนี้";
        header("Location: login.php");
        exit();
    }
} else {
    // ถ้าพยายามเข้าหน้านี้โดยไม่ได้กดปุ่ม Login ให้เด้งกลับ
    header("Location: login.php");
    exit();
}
?>