<?php
session_start();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';

if (!empty($id)) {
    if ($action == 'add') {
        // เพิ่มจำนวนสินค้า
        if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = array(); }
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]++;
        } else {
            $_SESSION['cart'][$id] = 1;
        }
    } 
    elseif ($action == 'remove') {
        // ลบสินค้าชิ้นที่เลือกออกจากตะกร้า
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
    }
}

// หลังจากจัดการเสร็จ ให้ส่งกลับไปหน้าเดิม (เช่น menu.php หรือ cart.php)
if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    header("Location: menu.php");
}
exit();
?>