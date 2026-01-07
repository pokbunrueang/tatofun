<?php
session_start();

// ✅ 1. ล้างค่า Session ทั้งหมดที่เคยจดจำไว้ (เช่น ชื่อ, บทบาท)
$_SESSION = array();

// ✅ 2. ทำลาย Cookie ของ Session (ถ้ามี) เพื่อป้องกันการค้างของข้อมูลในบราวเซอร์
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// ✅ 3. ทำลาย Session ในฝั่ง Server
session_destroy();

// ✅ 4. เริ่ม Session ใหม่ชั่วคราวเพื่อส่งข้อความแจ้งเตือน (Optional)
session_start();
$_SESSION['success'] = "ออกจากระบบเรียบร้อยแล้ว";

// ✅ 5. ส่งกลับไปหน้า Login เพื่อความปลอดภัย (หรือหน้า index.php ตามที่คุณต้องการ)
header("Location: login.php"); 
exit();
?>