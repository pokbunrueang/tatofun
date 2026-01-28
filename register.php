<?php
session_start();
include 'config.php';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']); 
    $address = mysqli_real_escape_string($conn, $_POST['address']); 
    $role = 'member';

    $check_user = "SELECT * FROM tb_users WHERE username = '$username'";
    $query_check = mysqli_query($conn, $check_user);

    if (mysqli_num_rows($query_check) > 0) {
        $error = "ชื่อผู้ใช้นี้ถูกใช้ไปแล้ว กรุณาเปลี่ยนใหม่";
    } else {
        $sql1 = "INSERT INTO tb_users (username, password, role) VALUES ('$username', '$password', '$role')";
        if (mysqli_query($conn, $sql1)) {
            $last_id = mysqli_insert_id($conn);
            $sql2 = "INSERT INTO tb_customer_profile (user_id, fullname, phone, address) 
                     VALUES ('$last_id', '$fullname', '$phone', '$address')";
            
            if (mysqli_query($conn, $sql2)) {
                $_SESSION['success'] = "สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ";
                header("Location: login.php");
                exit();
            } else {
                $error = "เกิดข้อผิดพลาดในการบันทึกโปรไฟล์: " . mysqli_error($conn);
            }
        } else {
            $error = "เกิดข้อผิดพลาดในการสร้างบัญชี: " . mysqli_error($conn);
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 40px 0;
            background-color: #ffc107; /* สีเหลืองหลัก */
            overflow-x: hidden;
            position: relative;
        }

        /* --- พื้นหลังไล่ระดับสี (เหมือนหน้า Login ที่คุณชอบ) --- */
        .bg-gradient-layer {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, #ffca28 0%, #ffb300 100%);
            z-index: -3;
        }
        .bg-glow {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
            z-index: -2;
        }

        /* --- ตั้งค่ามันฝรั่งลอยกระจาย --- */
        .floating-potato {
            position: fixed;
            z-index: -1;
            filter: drop-shadow(0 8px 15px rgba(0,0,0,0.1));
            pointer-events: none;
            opacity: 0;
        }

        /* --- การ์ดสมัครสมาชิกสไตล์คลีน --- */
        .register-card {
            width: 100%;
            max-width: 480px;
            padding: 40px;
            border-radius: 40px;
            background: #ffffff;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 10;
            border: none;
        }

        .brand-logo { width: 80px; display: block; margin: 0 auto 15px; }
        .form-control { border-radius: 15px; padding: 12px; background-color: #fafafa; border: 1px solid #f0f0f0; }
        .form-control:focus { border-color: #ffb300; box-shadow: 0 0 0 3px rgba(255, 179, 0, 0.1); }
        
        .btn-register { 
            background: #ffb300; 
            color: #fff; 
            border-radius: 15px; 
            padding: 13px; 
            width: 100%; 
            border: none; 
            font-weight: 600; 
            font-size: 1.1rem;
            transition: 0.3s; 
        }
        .btn-register:hover { 
            background: #ffa000; 
            transform: translateY(-2px); 
            box-shadow: 0 10px 20px rgba(255, 160, 0, 0.2); 
        }
        
        .login-link { color: #ff9800; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>

    <div class="bg-gradient-layer"></div>
    <div class="bg-glow"></div>
    <div id="potato-container"></div>

    <div class="register-card">
        <img src="admin/img_ad/LOGO3.png" alt="Logo" class="brand-logo" onerror="this.src='https://cdn-icons-png.flaticon.com/512/1135/1135544.png'">
        <h3 class="fw-bold text-center mb-1">สร้างบัญชีใหม่</h3>
        <p class="text-muted small text-center mb-4">เข้าร่วมเป็นสมาชิก TatoFun วันนี้!</p>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger py-2 small border-0 mb-3 text-center">
                <i class="bi bi-exclamation-circle-fill"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold ms-2">ชื่อ-นามสกุล</label>
                <input type="text" name="fullname" class="form-control" placeholder="ตัวอย่าง: สมชาย ใจดี" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold ms-2">เบอร์โทรศัพท์</label>
                <input type="text" name="phone" class="form-control" placeholder="08X-XXXXXXX" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold ms-2">ที่อยู่ปัจจุบัน</label>
                <textarea name="address" class="form-control" rows="2" placeholder="บ้านเลขที่, ถนน, ตำบล..." required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold ms-2">ชื่อผู้ใช้งาน (Username)</label>
                <input type="text" name="username" class="form-control" placeholder="สำหรับใช้เข้าสู่ระบบ" required>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold ms-2">รหัสผ่าน</label>
                <input type="password" name="password" class="form-control" placeholder="ตั้งรหัสผ่านของคุณ" required>
            </div>
            
            <button type="submit" name="register" class="btn btn-register mb-4 shadow-sm">ยืนยันสมัครสมาชิก</button>
            
            <div class="text-center">
                <span class="small text-muted">มีบัญชีอยู่แล้ว? </span>
                <a href="login.php" class="small login-link">เข้าสู่ระบบ</a>
            </div>
        </form>
    </div>

    <script>
        function spawnPotato() {
            const container = document.getElementById('potato-container');
            const p = document.createElement('div');
            p.className = 'floating-potato';
            
            const imgUrl = 'admin/img_ad/LOGO3.png'; 
            const size = Math.random() * (80 - 40) + 40; 
            
            p.innerHTML = `<img src="${imgUrl}" style="width: ${size}px; transform: rotate(${Math.random() * 360}deg);">`;
            
            const startX = Math.random() * 100;
            p.style.left = startX + 'vw';
            p.style.top = '110vh';
            
            container.appendChild(p);

            const drift = (Math.random() - 0.5) * 30; 
            const duration = Math.random() * (18000 - 10000) + 10000;

            p.animate([
                { transform: `translateY(0) translateX(0) rotate(0deg)`, opacity: 0 },
                { opacity: 0.7, offset: 0.2 }, 
                { opacity: 0.7, offset: 0.8 }, 
                { transform: `translateY(-125vh) translateX(${drift}vw) rotate(${Math.random() * 720}deg)`, opacity: 0 }
            ], {
                duration: duration,
                easing: 'linear'
            }).onfinish = () => p.remove();
        }

        setInterval(spawnPotato, 2000);
        for(let i=0; i<5; i++) {
            setTimeout(spawnPotato, i * 800);
        }
    </script>
</body>
</html>