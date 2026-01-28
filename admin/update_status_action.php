<?php
include '../config.php'; 
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // แก้ไขให้เป็น menu_stock เพื่อให้ตรงกับโครงสร้างตารางของคุณ
    $sql = "UPDATE tb_menu SET menu_stock = '$status' WHERE id_menu = '$id'"; 
    
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database Error: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid Request']);
}
exit();
?>