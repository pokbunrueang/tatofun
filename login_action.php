<?php
session_start();
require_once 'config.php'; 

if (isset($_POST['btn_login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pass = $_POST['pass']; 

    $sql = "SELECT user_id, username, password, fullname, role FROM tb_users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        
        if ($pass === $row['password']) { 
            session_regenerate_id(true);

            $_SESSION['user_id']  = $row['user_id'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['role']     = $row['role']; // เก็บค่า admin, staff, member

            // ✅ ปรับส่วนนี้ตามที่คุณต้องการ: แยกหน้าตามสิทธิ์
            switch ($row['role']) {
                case 'admin':
                case 'staff':
                    // ส่ง Admin และ Staff ไปหน้าจัดการหลังบ้านที่เดียวกัน
                    header("Location: admin/index_ad.php");
                    break;
                case 'member':
                    // ส่งลูกค้า (Member) กลับไปหน้าหลักเพื่อดูเมนูและสั่งอาหาร
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
    header("Location: login.php");
    exit();
}

// เมื่อเช็ครหัสผ่านผ่านแล้ว
$_SESSION['user_id'] = $row['user_id'];
$_SESSION['role'] = $row['role'];

$user_id = $row['user_id'];

if ($row['role'] == 'member') {
    // ถ้าเป็นลูกค้า ให้ไปดึงชื่อจากตารางโปรไฟล์ลูกค้า
    $sql_p = "SELECT fullname FROM tb_customer_profile WHERE user_id = '$user_id'";
    $res_p = mysqli_query($conn, $sql_p);
    $row_p = mysqli_fetch_assoc($res_p);
    $_SESSION['fullname'] = $row_p['fullname'];
    header("Location: index.php");
} else {
    // ถ้าเป็น admin หรือ staff ให้ไปดึงชื่อจากตารางโปรไฟล์พนักงาน
    $sql_p = "SELECT fullname FROM tb_staff_profile WHERE user_id = '$user_id'";
    $res_p = mysqli_query($conn, $sql_p);
    $row_p = mysqli_fetch_assoc($res_p);
    $_SESSION['fullname'] = $row_p['fullname'];
    header("Location: admin/index_ad.php");
}
exit();

?>