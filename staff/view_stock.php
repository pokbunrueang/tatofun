<?php
session_start();
include '../config.php';

// 1. ตรวจสอบสิทธิ์พนักงาน
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit();
}

// 2. ดึงข้อมูลเมนู
$sql = "SELECT * FROM tb_menu ORDER BY id_menu ASC";
$result = mysqli_query($conn, $sql);
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการสต็อก - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --tato-yellow: #ffca28; --tato-orange: #f57c00; }
        body { background: #f4f7f6; font-family: 'Kanit', sans-serif; }
        .stock-card { border: none; border-radius: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); background: white; overflow: hidden; }
        .table thead { background: #2c3e50; color: white; }
        .table th { border: none; padding: 20px; font-weight: 400; }
        .menu-img-container { width: 75px; height: 75px; border-radius: 20px; overflow: hidden; border: 3px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .menu-img { width: 100%; height: 100%; object-fit: cover; }
        .status-dropdown { border-radius: 15px; padding: 10px 15px; font-weight: 600; border: 2px solid transparent; transition: 0.3s all; cursor: pointer; }
        .status-ready { background-color: #d1e7dd !important; color: #0f5132 !important; border-color: #badbcc !important; }
        .status-out { background-color: #f8d7da !important; color: #842029 !important; border-color: #f5c2c7 !important; }
        .text-orange { color: var(--tato-orange); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="index_st.php" class="btn btn-white rounded-pill shadow-sm border-0 bg-white">
            <i class="bi bi-arrow-left me-2"></i>กลับหน้าหลัก
        </a>
        <h2 class="fw-bold mb-0">ระบบจัดการสต็อก</h2>
        <div class="badge bg-white text-dark rounded-pill p-3 shadow-sm border border-warning">
            <i class="bi bi-clock-fill text-warning me-2"></i><span id="liveClock"><?= date('H:i:s') ?></span>
        </div>
    </div>

    <div class="card stock-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center">รูป</th>
                        <th>ชื่อเมนู / ราคา</th>
                        <th>สถานะปัจจุบัน</th>
                        <th class="text-center">แจ้งฝ่ายบริหาร</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): 
                            // แก้ไขเป็น menu_stock ตามโครงสร้างฐานข้อมูลที่คุณส่งรูปมา
                            $current_status = isset($row['menu_stock']) ? $row['menu_stock'] : 1;
                            $status_class = ($current_status == 1) ? 'status-ready' : 'status-out';
                        ?>
                        <tr>
                            <td class="text-center p-4">
                                <div class="menu-img-container mx-auto">
                                    <?php $img = !empty($row['img_menu']) ? "../admin/img_ad/".$row['img_menu'] : "../img/no1.png"; ?>
                                    <img src="<?= $img ?>" class="menu-img" onerror="this.src='https://via.placeholder.com/80?text=Food'">
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold fs-5"><?= htmlspecialchars($row['name_menu']) ?></div>
                                <span class="text-orange fw-bold">฿ <?= number_format($row['price_menu']) ?></span>
                            </td>
                            <td>
                                <select class="form-select status-dropdown shadow-sm <?= $status_class ?>" 
                                        data-id="<?= $row['id_menu']; ?>" 
                                        onchange="updateStatus(this)">
                                    <option value="1" <?= ($current_status == 1) ? 'selected' : ''; ?>>🟢 พร้อมจำหน่าย</option>
                                    <option value="0" <?= ($current_status == 0) ? 'selected' : ''; ?>>🔴 สินค้าหมด</option>
                                </select>
                            </td>
                            <td class="text-center">
                                <button onclick="notifyAdmin('<?= htmlspecialchars($row['name_menu']) ?>')" class="btn btn-outline-danger btn-sm rounded-pill px-3 border-0 bg-danger bg-opacity-10 py-2">
                                    <i class="bi bi-megaphone-fill me-1"></i> แจ้งด่วน
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center p-5 text-muted">ไม่พบข้อมูลเมนู</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function updateStatus(selectElement) {
    const menuId = selectElement.getAttribute('data-id');
    const newStatus = selectElement.value;

    fetch('../admin/update_status_action.php', { 
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${menuId}&status=${newStatus}`
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // สลับ Class สีทันที
            if (newStatus == "1") {
                selectElement.classList.replace('status-out', 'status-ready');
            } else {
                selectElement.classList.replace('status-ready', 'status-out');
            }
            Swal.fire({ icon: 'success', title: 'อัปเดตสต็อกเรียบร้อย', toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });
        } else {
            Swal.fire({ icon: 'error', title: 'ผิดพลาด!', text: data.message });
        }
    })
    .catch(err => Swal.fire({ icon: 'error', title: 'ล้มเหลว', text: 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้' }));
}

function notifyAdmin(name) {
    Swal.fire({
        title: 'ยืนยันแจ้งฝ่ายบริหาร?',
        text: `คุณกำลังแจ้งว่า '${name}' หมดชั่วคราว`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffca28',
        confirmButtonText: 'ยืนยันส่ง',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('สำเร็จ!', 'ระบบส่งเรื่องให้ฝ่ายบริหารแล้ว', 'success');
        }
    });
}

setInterval(() => { document.getElementById('liveClock').innerText = new Date().toLocaleTimeString('th-TH'); }, 1000);
</script>
</body>
</html>