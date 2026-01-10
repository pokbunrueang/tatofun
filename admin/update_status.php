<?php
session_start();
include '../config.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $order_id = mysqli_real_escape_string($conn, $_GET['id']);
    $new_status = mysqli_real_escape_string($conn, $_GET['status']);

    // อัปเดตสถานะในตาราง tb_orders
    $sql = "UPDATE tb_orders SET status = '$new_status' WHERE order_id = '$order_id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: manage_orders.php?msg=success");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>