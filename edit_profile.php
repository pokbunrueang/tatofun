<?php
session_start();
include 'config.php';

// 1. ตรวจสอบ Login
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}
$user_id = $_SESSION['user_id'];

// 2. ดึงข้อมูลโปรไฟล์
$sql = "SELECT * FROM tb_customer_profile WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// ถ้าไม่มีข้อมูล (User ใหม่) ให้สร้างค่าว่างรอไว้
if (!$user) {
    $user = ['fullname' => '', 'phone' => '', 'address' => '', 'user_img' => ''];
}

// 3. จัดการเมื่อกดบันทึก
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']); 
    $address  = mysqli_real_escape_string($conn, $_POST['address']);
    $img_name = $user['user_img']; 

    // จัดการอัปโหลดรูป
    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] == 0) {
        $ext = pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION);
        $new_name = "user_" . $user_id . "_" . time() . "." . $ext;
        $target = "uploads/profile/" . $new_name;
        
        if (!is_dir("uploads/profile")) { mkdir("uploads/profile", 0777, true); }

        if (move_uploaded_file($_FILES['profile_img']['tmp_name'], $target)) {
            $img_name = $new_name;
        }
    }

    // ใช้ REPLACE INTO เพื่อให้รองรับทั้ง Insert และ Update ในคำสั่งเดียว
    $update_sql = "REPLACE INTO tb_customer_profile (user_id, fullname, phone, address, user_img) 
                   VALUES ('$user_id', '$fullname', '$phone', '$address', '$img_name')";

    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('✨ บันทึกเรียบร้อยแล้วนะ!'); window.location='profile.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>เกิดข้อผิดพลาด: " . mysqli_error($conn) . "</div>";
    }
}

// กำหนดรูปภาพที่จะโชว์ (เปลี่ยนจากรูปการ์ตูนเป็น Icon มินิมอล)
$default_img = "https://cdn-icons-png.flaticon.com/512/1144/1144760.png";
$preview_path = "uploads/profile/" . ($user['user_img'] ?? '');
$display_img = (!empty($user['user_img']) && file_exists($preview_path)) ? $preview_path : $default_img;
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขโปรไฟล์ - TatoFun</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { background-color: #fffaf2; font-family: 'Kanit', sans-serif; color: #5a4b3f; }
        .edit-card { border-radius: 30px; border: none; box-shadow: 0 10px 30px rgba(255,157,46,0.1); background: #fff; margin-top: 50px; overflow: hidden; }
        .header-section { background: linear-gradient(135deg, #ff9d2e 0%, #ffc107 100%); padding: 40px 20px; color: white; text-align: center; }
        .profile-img-preview { width: 130px; height: 130px; object-fit: cover; border-radius: 50%; border: 5px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1); background: #f8f9fa; margin-top: -65px; position: relative; z-index: 2; }
        .btn-camera { position: absolute; bottom: 0; right: 5px; background: #ff9d2e; color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid #fff; cursor: pointer; transition: 0.3s; }
        .btn-camera:hover { background: #e68a24; transform: scale(1.1); }
        .form-control { border: 2px solid #f8f1e9; padding: 12px 20px; border-radius: 15px !important; transition: 0.3s; }
        .form-control:focus { border-color: #ff9d2e; box-shadow: none; }
        .btn-save { background: linear-gradient(to right, #ff9d2e, #ffb300); border: none; border-radius: 15px; padding: 12px; font-weight: 600; color: white; }
    </style>
</head>
<body>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card edit-card shadow">
                <div class="header-section">
                    <h4 class="fw-bold mb-1">แก้ไขข้อมูลส่วนตัว ✨</h4>
                    <p class="small opacity-75 mb-0">ตกแต่งโปรไฟล์ให้ดูดีในแบบคุณ</p>
                </div>
                
                <div class="card-body p-4 pt-0 text-center">
                    <form action="" method="POST" enctype="multipart/form-data">
                        
                        <div class="d-inline-block position-relative mb-4">
                            <img src="<?= $display_img ?>" id="previewImg" class="profile-img-preview">
                            <label for="imgInput" class="btn-camera shadow-sm"><i class="bi bi-camera-fill"></i></label>
                            <input type="file" name="profile_img" id="imgInput" hidden accept="image/*">
                            <p class="small text-muted mt-2">คลิกไอคอนกล้องเพื่อเปลี่ยนรูป</p>
                        </div>

                        <div class="text-start">
                            <div class="mb-3">
                                <label class="form-label small ms-2 fw-bold">ชื่อ-นามสกุล</label>
                                <input type="text" name="fullname" class="form-control" 
                                       value="<?= htmlspecialchars($user['fullname']) ?>" placeholder="ระบุชื่อของคุณ" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small ms-2 fw-bold">เบอร์โทรศัพท์</label>
                                <input type="text" name="phone" class="form-control" 
                                       value="<?= htmlspecialchars($user['phone']) ?>" placeholder="08x-xxx-xxxx">
                            </div>

                            <div class="mb-4">
                                <label class="form-label small ms-2 fw-bold">ที่อยู่สำหรับจัดส่ง 📦</label>
                                <textarea name="address" class="form-control" rows="3" placeholder="บ้านเลขที่, ถนน, แขวง/ตำบล..."><?= htmlspecialchars($user['address']) ?></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-save shadow-sm">บันทึกข้อมูล</button>
                                <a href="profile.php" class="btn btn-light rounded-4 py-2 text-muted text-decoration-none">ยกเลิก</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview รูปทันทีที่เลือก
    document.getElementById('imgInput').onchange = evt => {
        const [file] = document.getElementById('imgInput').files;
        if (file) { 
            document.getElementById('previewImg').src = URL.createObjectURL(file); 
        }
    }
</script>
</body>
</html>