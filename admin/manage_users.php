<?php 
session_start();
include '../config.php'; 

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php"); 
    exit();
}

// ดึงข้อมูลผู้ใช้ทั้งหมด เรียงจากสมัครล่าสุด
$sql = "SELECT id, username, cus_name, email, cus_tel, role, reg_date FROM tb_users ORDER BY reg_date DESC";
$result = mysqli_query($conn, $sql);
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการสมาชิก - TatoFun Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-bg: #f8f9fa;
        }
        body { 
            background-color: var(--secondary-bg); 
            font-family: 'Kanit', sans-serif; 
            color: #333;
        }
        .main-card { 
            border-radius: 20px; 
            border: none; 
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            background: #fff;
            overflow: hidden;
        }
        .table thead th {
            background-color: #fff;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            font-weight: 600;
            padding: 20px;
            color: #6c757d;
            border-bottom: 2px solid #f1f1f1;
        }
        .table tbody td {
            padding: 18px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #f8f9fa;
        }
        .avatar-circle { 
            width: 45px; 
            height: 45px; 
            background: linear-gradient(135deg, #e0e7ff 0%, #ede9fe 100%);
            border-radius: 12px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: var(--primary-color);
            font-size: 1.2rem;
            font-weight: bold;
        }
        .badge-admin {
            background-color: #fff1f2;
            color: #e11d48;
            border: 1px solid #ffe4e6;
        }
        .badge-user {
            background-color: #f0f9ff;
            color: #0369a1;
            border: 1px solid #e0f2fe;
        }
        .btn-action {
            width: 35px;
            height: 35px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: 0.2s;
        }
        .search-box {
            border-radius: 15px;
            border: 1px solid #e2e8f0;
            padding: 10px 20px;
            background: #fff;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h2 class="fw-bold mb-1">จัดการ<span class="text-primary">สมาชิก</span></h2>
            <p class="text-muted mb-0">ดูแลและตรวจสอบบัญชีผู้ใช้งานทั้งหมดในระบบ</p>
        </div>
        <div class="d-flex gap-2">
            <a href="index_ad.php" class="btn btn-white shadow-sm rounded-pill px-4 border">
                <i class="bi bi-house-door me-2"></i>หน้าหลัก
            </a>
        </div>
    </div>

    <div class="card main-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">ผู้ใช้งาน</th>
                        <th>ข้อมูลติดต่อ</th>
                        <th>วันที่สมัคร</th>
                        <th class="text-center">สิทธิ์การใช้งาน</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle">
                                        <?= strtoupper(substr($row['username'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark"><?= htmlspecialchars($row['cus_name']) ?></div>
                                        <div class="small text-muted">@<?= htmlspecialchars($row['username']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="small"><i class="bi bi-envelope me-2 text-muted"></i><?= $row['email'] ?></span>
                                    <span class="small"><i class="bi bi-telephone me-2 text-muted"></i><?= $row['cus_tel'] ?? 'ไม่ได้ระบุ' ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="small text-dark">
                                    <?= date('d M Y', strtotime($row['reg_date'])) ?>
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    <?= date('H:i', strtotime($row['reg_date'])) ?> น.
                                </div>
                            </td>
                            <td class="text-center">
                                <?php if($row['role'] == 'admin'): ?>
                                    <span class="badge badge-admin px-3 py-2 rounded-pill">
                                        <i class="bi bi-shield-lock me-1"></i> ADMIN
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-user px-3 py-2 rounded-pill">
                                        <i class="bi bi-person me-1"></i> USER
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-action border shadow-sm" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2">
                                        <li>
                                            <a class="dropdown-item rounded-3 py-2" href="edit_user.php?id=<?= $row['id'] ?>">
                                                <i class="bi bi-pencil me-2 text-primary"></i>แก้ไขข้อมูล
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider opacity-50"></li>
                                        <li>
                                            <a class="dropdown-item rounded-3 py-2 text-danger" 
                                               href="delete_user.php?id=<?= $row['id'] ?>" 
                                               onclick="return confirm('❗ คุณแน่ใจหรือไม่ที่จะลบสมาชิก @<?= $row['username'] ?>?\nการกระทำนี้ไม่สามารถย้อนกลับได้')">
                                                <i class="bi bi-trash3 me-2"></i>ลบบัญชี
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-people fs-1 text-muted opacity-25 d-block mb-3"></i>
                                <p class="text-muted">ไม่พบข้อมูลสมาชิกในระบบ</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>