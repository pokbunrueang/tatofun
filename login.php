<?php
session_start();
include 'config.php'; 

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // ค้นหาผู้ใช้จากตาราง tb_users
    $sql = "SELECT * FROM tb_users WHERE username = '$username' AND password = '$password'";
    $query = mysqli_query($conn, $sql);
    
    if ($query) {
        $result = mysqli_fetch_array($query); 
        if ($result) {
            // เก็บ Session ให้ครบถ้วน
            $_SESSION['user_id']  = $result['user_id']; 
            $_SESSION['role']     = $result['role']; 
            $_SESSION['fullname'] = $result['fullname']; 
            
            // ใช้ id_member อ้างอิงสำหรับหน้า Checkout
            $_SESSION['id_member'] = $result['user_id']; 

            // แยกการส่งหน้าตามระดับสิทธิ์ (Role)
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
        body { font-family: 'Kanit', sans-serif; margin: 0; height: 100vh; display: flex; align-items: center; justify-content: center; background-color: #ffca28; overflow: hidden; position: relative; }
        .bg-gradient-layer { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #ffca28 0%, #ffb300 100%); z-index: -3; }
        .bg-glow { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.4) 0%, transparent 50%), radial-gradient(circle at 80% 70%, rgba(255, 255, 255, 0.4) 0%, transparent 50%); z-index: -2; }
        .floating-potato { position: absolute; z-index: -1; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1)); pointer-events: none; transition: transform 15s linear, opacity 15s linear; }
        .login-card { width: 100%; max-width: 420px; padding: 40px; border-radius: 40px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(15px); box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1); text-align: center; border: 1px solid rgba(255, 255, 255, 0.5); z-index: 10; }
        .brand-logo { width: 90px; margin-bottom: 15px; }
        .form-control { border-radius: 15px; padding: 12px; border: 1px solid #eee; background-color: #fcfcfc; }
        .btn-login { background: #ffca28; color: #6d4c41; border-radius: 15px; padding: 12px; width: 100%; border: none; font-weight: 600; transition: 0.3s; }
        .btn-login:hover { background: #ffb300; transform: scale(1.03); }
    </style>
</head>
<body>
    <div class="bg-gradient-layer"></div>
    <div class="bg-glow"></div>
    <div id="potato-container"></div> 

    <div class="login-card">
        <img src="admin/img_ad/LOGO3.png" alt="Logo" class="brand-logo" onerror="this.src='https://cdn-icons-png.flaticon.com/512/1135/1135544.png'">
        <h3 class="fw-bold mb-1">ยินดีต้อนรับ</h3>
        <p class="text-muted small mb-4">เข้าสู่ระบบ TatoFun</p>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger py-2 small border-0 mb-3 text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3 text-start">
                <label class="form-label small fw-bold ms-2">ชื่อผู้ใช้งาน</label>
                <input type="text" name="username" class="form-control" placeholder="ระบุชื่อผู้ใช้" required>
            </div>
            <div class="mb-4 text-start">
                <label class="form-label small fw-bold ms-2">รหัสผ่าน</label>
                <input type="password" name="password" class="form-control" placeholder="ระบุรหัสผ่าน" required>
            </div>
            <button type="submit" name="login" class="btn btn-login mb-3 shadow-sm">เข้าสู่ระบบ</button>
            <div class="mb-4">
                <span class="small text-muted">ยังไม่มีบัญชี?</span>
                <a href="register.php" class="small text-decoration-none fw-bold" style="color: #f57c00;">สมัครสมาชิกที่นี่</a>
            </div>
            <hr class="text-muted opacity-25">
            <a href="index.php" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left"></i> กลับสู่หน้าหลัก</a>
        </form>
    </div>

    <script>
        function spawnPotato() {
            const container = document.getElementById('potato-container');
            const potato = document.createElement('div');
            potato.className = 'floating-potato';
            const imgUrl = 'admin/img_ad/LOGO3.png'; 
            const size = Math.random() * (90 - 45) + 45;
            potato.innerHTML = `<img src="${imgUrl}" style="width: ${size}px; opacity: 0.8;" onerror="this.src='https://cdn-icons-png.flaticon.com/512/1135/1135544.png'">`;
            potato.style.left = Math.random() * 100 + 'vw';
            potato.style.bottom = '-100px';
            container.appendChild(potato);
            setTimeout(() => {
                const randomX = (Math.random() - 0.5) * 200;
                potato.style.transform = `translate(${randomX}px, -120vh) rotate(${Math.random() * 360}deg)`;
                potato.style.opacity = '0';
            }, 100);
            setTimeout(() => { potato.remove(); }, 15000);
        }
        setInterval(spawnPotato, 2500);
        for(let i=0; i<6; i++) { setTimeout(spawnPotato, i * 500); }
    </script>
</body>
</html>