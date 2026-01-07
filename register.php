<?php
session_start();
include 'config.php';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $role = 'member'; // กำหนดให้ผู้สมัครใหม่เป็นสมาชิกทั่วไป

    // ตรวจสอบว่าชื่อผู้ใช้ซ้ำหรือไม่
    $check_user = "SELECT * FROM tb_users WHERE username = '$username'";
    $query_check = mysqli_query($conn, $check_user);

    if (mysqli_num_rows($query_check) > 0) {
        $error = "ชื่อผู้ใช้นี้ถูกใช้ไปแล้ว กรุณาเปลี่ยนใหม่";
    } else {
        // บันทึกข้อมูล (แนะนำให้ใช้ password_hash ในอนาคตเพื่อความปลอดภัย)
        $sql = "INSERT INTO tb_users (username, password, fullname, role) 
                VALUES ('$username', '$password', '$fullname', '$role')";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['success'] = "สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ";
            header("Location: login.php"); // สมัครเสร็จส่งไปหน้า login
            exit();
        } else {
            $error = "เกิดข้อผิดพลาด: " . mysqli_error($conn);
        }
    }
}
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>สมัครสมาชิก - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');
        body {
            font-family: 'Kanit', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(-45deg, #ffb300, #ffcc33, #ff9100, #ffd600);
            background-size: 400% 400%;
            animation: gradientBG 10s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .register-card {
            width: 100%;
            max-width: 450px;
            padding: 40px;
            border-radius: 30px;
            background: #ffffff; 
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        .brand-logo { width: 70px; display: block; margin: 0 auto 15px; }
        .form-control { border-radius: 12px; padding: 10px; background-color: #fcfcfc; }
        .btn-register { background: #333; color: white; border-radius: 12px; padding: 12px; width: 100%; border: none; font-weight: 600; transition: 0.3s; }
        .btn-register:hover { background: #000; transform: translateY(-2px); }
    </style>
</head>
<body>

    <div class="register-card">
        <img src="admin/img_ad/LOGO3.png" alt="Logo" class="brand-logo">
        <h3 class="fw-bold text-center mb-1">สร้างบัญชีใหม่</h3>
        <p class="text-muted small text-center mb-4">เข้าร่วมเป็นสมาชิก TatoFun วันนี้!</p>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger py-2 small border-0 mb-3 text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold ms-2">ชื่อ-นามสกุล</label>
                <input type="text" name="fullname" class="form-control" placeholder="ตัวอย่าง: สมชาย ใจดี" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold ms-2">ชื่อผู้ใช้งาน (Username)</label>
                <input type="text" name="username" class="form-control" placeholder="สำหรับใช้เข้าสู่ระบบ" required>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold ms-2">รหัสผ่าน</label>
                <input type="password" name="password" class="form-control" placeholder="ตั้งรหัสผ่านของคุณ" required>
            </div>
            <button type="submit" name="register" class="btn btn-register mb-3 shadow-sm">ยืนยันสมัครสมาชิก</button>
            <div class="text-center">
                <span class="small text-muted">มีบัญชีอยู่แล้ว? </span>
                <a href="login.php" class="small fw-bold text-dark text-decoration-none">เข้าสู่ระบบ</a>
            </div>
        </form>
    </div>

</body>
</html>