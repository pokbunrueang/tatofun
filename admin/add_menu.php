<!--เสร็จแล้ว-->
<?php
include('../config.php');

if(isset($_POST['submit'])){
    $name = $_POST['name_menu'];
    $price = $_POST['price_menu'];
    
    // คำสั่ง SQL เพิ่มข้อมูลลงตาราง tb_menu ตามที่คุณตั้งชื่อไว้
    $sql = "INSERT INTO tb_menu (name_menu, price_menu) VALUES ('$name', '$price')";
    
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('เพิ่มเมนูสำเร็จ!'); window.location='manage_menu.php';</script>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>เพิ่มเมนูใหม่ - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white"><h4>เพิ่มเมนูใหม่ 🍟</h4></div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">ชื่อเมนู</label>
                                <input type="text" name="name_menu" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ราคา (บาท)</label>
                                <input type="number" name="price_menu" class="form-control" required>
                            </div>
                            <button type="submit" name="submit" class="btn btn-success w-100">บันทึกเมนู</button>
                            <a href="manage_menu.php" class="btn btn-secondary w-100 mt-2">ยกเลิก</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>