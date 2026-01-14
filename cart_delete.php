<?php
session_start();
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'clear') {
    // ล้างสินค้าทั้งหมดในตะกร้า
    unset($_SESSION['cart']);
} else {
    // ลบเฉพาะรายการที่เลือก
    $id = $_GET['id'];
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
}

header("Location: cart.php");
exit();
?>