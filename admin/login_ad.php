<?php
session_start();
// ตัวอย่าง Username/Password (ในอนาคตเปลี่ยนไปดึงจาก Database ได้ครับ)
$admin_user = "admin";
$admin_pass = "1234";

if (isset($_POST['login'])) {
    if ($_POST['username'] == $admin_user && $_POST['password'] == $admin_pass) {
        $_SESSION['admin_login'] = true;
        header("Location: index_ad.php");
    } else {
        $error = "รหัสผ่านไม่ถูกต้อง!";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #ffb300; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { width: 100%; max-width: 400px; padding: 30px; border-radius: 15px; background: white; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="login-card text-center">
        <img src="img_ad/LOGO3.png" alt="Logo" width="100" class="mb-4">
        <h3 class="fw-bold mb-4">เข้าสู่ระบบผู้ดูแล</h3>
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3 text-start">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-dark w-100 py-2">เข้าสู่ระบบ</button>
        </form>
    </div>
</body>
</html>