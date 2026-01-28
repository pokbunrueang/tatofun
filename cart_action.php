<?php
session_start();
include 'config.php';

// รับค่า id และ action (ถ้าไม่มี action ให้ถือว่าเป็น 'add')
$id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : 'add';

if (!empty($id)) {
    // 1. ถ้ายังไม่มีตะกร้าใน Session ให้สร้างเป็น Array ว่าง
    if (!isset($_SESSION['cart'])) { 
        $_SESSION['cart'] = array(); 
    }

    if ($action == 'add') {
        // --- ส่วนเพิ่มสินค้า ---
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]++;
        } else {
            $_SESSION['cart'][$id] = 1;
        }
    } 
    elseif ($action == 'remove') {
        // --- ส่วนลบสินค้า (สำหรับใช้ในหน้าตะกร้า) ---
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
    }
}

// หลังจากจัดการ Session เสร็จ ให้ส่งกลับไปหน้าเดิมที่กดมา
if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    header("Location: index.php");
}
exit();
?>