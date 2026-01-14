<?php
session_start();
include '../config.php';

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// รับค่า ID ที่จะแก้ไข
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tb_menu WHERE id_menu = $id"; // ใช้ id_menu ตามโครงสร้างตารางคุณ
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
}

// เมื่อมีการกดปุ่มบันทึก
if (isset($_POST['update'])) {
    $id_menu = $_POST['id_menu'];
    $name_menu = $_POST['name_menu'];
    $price_menu = $_POST['price_menu'];
    
    // จัดการรูปภาพ (ถ้ามีการอัปโหลดใหม่)
    if ($_FILES['img_menu']['name'] != "") {
        $ext = pathinfo($_FILES['img_menu']['name'], PATHINFO_EXTENSION);
        $new_name = "menu_" . time() . "." . $ext;
        copy($_FILES['img_menu']['tmp_name'], "img_ad/" . $new_name);
        $sql_update = "UPDATE tb_menu SET name_menu='$name_menu', price_menu='$price_menu', img_menu='$new_name' WHERE id_menu=$id_menu";
    } else {
        $sql_update = "UPDATE tb_menu SET name_menu='$name_menu', price_menu='$price_menu' WHERE id_menu=$id_menu";
    }

    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('แก้ไขข้อมูลสำเร็จ!'); window.location='manage_menu.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขเมนู - TatoFun Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');
        body { font-family: 'Kanit', sans-serif; background-color: #fffdf0; }
        .edit-card { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .btn-update { background-color: #ffc107; color: #000; border-radius: 12px; font-weight: 600; }
        .btn-update:hover { background-color: #e6ac00; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card edit-card p-4">
                    <h3 class="fw-bold mb-4 text-center"><i class="bi bi-pencil-square me-2"></i>แก้ไขรายการเมนู</h3>
                    
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id_menu" value="<?php echo $row['id_menu']; ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">ชื่อเมนู</label>
                            <input type="text" name="name_menu" class="form-control rounded-3" value="<?php echo $row['name_menu']; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">ราคา (บาท)</label>
                            <input type="number" name="price_menu" class="form-control rounded-3" value="<?php echo $row['price_menu']; ?>" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">รูปภาพปัจจุบัน</label><br>
                            <img src="img_ad/<?php echo $row['img_menu']; ?>" width="150" class="rounded-3 shadow-sm mb-3 border">
                            <input type="file" name="img_menu" class="form-control rounded-3">
                            <small class="text-muted">* ปล่อยว่างไว้หากไม่ต้องการเปลี่ยนรูป</small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="update" class="btn btn-update py-3 shadow-sm">บันทึกการแก้ไข</button>
                            <a href="manage_menu.php" class="btn btn-light rounded-3">ยกเลิก</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>