<?php
session_start();
include 'config.php'; 

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // ✅ ตรวจสอบจากตาราง tb_users
    $sql = "SELECT * FROM tb_users WHERE username = '$username' AND password = '$password'";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        $result = mysqli_fetch_array($query); 
        
        if ($result) {
            $_SESSION['role'] = $result['role']; 
            $_SESSION['fullname'] = $result['fullname']; 
            
            if($result['role'] == 'admin'){
                header("Location: admin/index_ad.php");
            } elseif($result['role'] == 'staff'){
                header("Location: staff/index_st.php"); 
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
        }
    } else {
        $error = "ข้อผิดพลาดฐานข้อมูล: " . mysqli_error($conn);
    }
}
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เข้าสู่ระบบ TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');

        body {
            font-family: 'Kanit', sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(-45deg, #ffb300, #ffcc33, #ff9100, #ffd600);
            background-size: 400% 400%;
            animation: gradientBG 10s ease infinite;
            overflow: hidden;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 30px;
            background: #ffffff; 
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .brand-logo { width: 85px; margin-bottom: 20px; }
        .form-control { border-radius: 15px; padding: 12px; border: 1px solid #eee; background-color: #fcfcfc; }
        .btn-login { background: #ffb300; color: white; border-radius: 15px; padding: 12px; width: 100%; border: none; font-weight: 600; font-size: 1.1rem; transition: 0.3s; }
        .btn-login:hover { background: #e6a100; transform: translateY(-2px); }
        .back-link { text-decoration: none; color: #888; font-size: 0.85rem; }
        .regis-link { text-decoration: none; color: #ffb300; font-weight: 600; }
        .regis-link:hover { color: #e6a100; text-decoration: underline; }
    </style>
</head>
<body>

    <div class="login-card shadow-lg">
        <img src="admin/img_ad/LOGO3.png" alt="Logo" class="brand-logo" onerror="this.src='https://via.placeholder.com/80?text=LOGO'">
        
        <h3 class="fw-bold mb-1">ยินดีต้อนรับ</h3>
        <p class="text-muted small mb-4">เข้าสู่ระบบ TatoFun</p>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success py-2 small border-0 mb-3 text-center">
                <i class="bi bi-check-circle-fill me-1"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger py-2 small border-0 mb-3 text-center">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3 text-start">
                <label class="form-label small fw-bold ms-2">ชื่อผู้ใช้งาน</label>
                <input type="text" name="username" class="form-control" placeholder="ระบุชื่อผู้ใช้" required autocomplete="off">
            </div>
            <div class="mb-4 text-start">
                <label class="form-label small fw-bold ms-2">รหัสผ่าน</label>
                <input type="password" name="password" class="form-control" placeholder="ระบุรหัสผ่าน" required>
            </div>
            
            <button type="submit" name="login" class="btn btn-login mb-3 shadow-sm">เข้าสู่ระบบ</button>
            
            <div class="mb-4">
                <span class="small text-muted">ยังไม่มีบัญชี?</span>
                <a href="register.php" class="small regis-link ms-1">สมัครสมาชิกที่นี่</a>
            </div>

            <hr class="text-muted opacity-25">
            
            <a href="index.php" class="back-link"><i class="bi bi-arrow-left"></i> กลับสู่หน้าหลักร้านค้า</a>
        </form>
    </div>

</body>
</html>