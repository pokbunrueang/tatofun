<?php
session_start();
include '../config.php';

// 1. ตรวจสอบสิทธิ์พนักงาน
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit();
}

// 2. ดึงข้อมูลเมนูจาก tb_menu
$sql = "SELECT * FROM tb_menu ORDER BY id_menu ASC";
$result = mysqli_query($conn, $sql);
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>คลังวัตถุดิบ - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Kanit', sans-serif; }
        .stock-card { border: none; border-radius: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .menu-img { width: 60px; height: 60px; object-fit: cover; border-radius: 15px; }
        
        /* สวิตช์เปิด-ปิดสไตล์ TatoFun */
        .form-check-input:checked { background-color: #10ac84; border-color: #10ac84; }
        .form-switch .form-check-input { width: 3em; height: 1.5em; cursor: pointer; }
        
        .status-on { color: #10ac84; font-weight: 600; }
        .text-orange { color: #f57c00; }
    </style>
</head>
<body>

<div class="container-fluid bg-white py-3 shadow-sm mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="index_st.php" class="btn btn-light rounded-pill px-3 shadow-sm border-0" style="background-color: #f8f9fa;">
            <i class="bi bi-arrow-left-circle me-1"></i> กลับหน้าหลัก
        </a>
        
        <h4 class="fw-bold mb-0" style="color: #ffc107;">
            <i class="bi bi-box-seam me-2"></i>ระบบจัดการสต็อกสินค้า
        </h4>
        
        <div style="width: 120px;" class="d-none d-md-block text-end">
            <span class="badge bg-light text-dark border rounded-pill p-2 px-3">
                <?=date('H:i')?> น.
            </span>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="card stock-card bg-white p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold m-0"><i class="bi bi-card-list me-2 text-warning"></i>รายการสินค้าและสถานะการขาย</h5>
            <div class="small text-muted">จัดการความพร้อมของวัตถุดิบวันนี้</div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="10%" class="ps-4">รูป</th>
                        <th width="35%">ชื่อเมนู</th>
                        <th width="20%">สถานะขาย</th>
                        <th width="35%" class="text-end pe-4">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="ps-4">
                            <?php $img = !empty($row['img_menu']) ? "../admin/uploads/".$row['img_menu'] : "../img/default.png"; ?>
                            <img src="<?= $img ?>" class="menu-img shadow-sm" onerror="this.src='https://via.placeholder.com/60?text=Food'">
                        </td>
                        <td>
                            <div class="fw-bold text-dark"><?= $row['name_menu'] ?: 'ไม่ระบุชื่อ' ?></div>
                            <small class="text-orange">ราคา <?= number_format($row['price_menu']) ?> ฿</small>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="sw_<?= $row['id_menu'] ?>" checked>
                                <label class="form-check-label ms-2 status-on" for="sw_<?= $row['id_menu'] ?>">พร้อมขาย</label>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <button onclick="notifyAdmin('<?= $row['name_menu'] ?>')" class="btn btn-outline-danger btn-sm rounded-pill px-3 me-2 border-0 bg-danger bg-opacity-10 text-danger">
                                <i class="bi bi-megaphone-fill me-1"></i> แจ้งของหมด
                            </button>
                            <button class="btn btn-dark btn-sm rounded-pill px-3 shadow-sm" onclick="toggleProduct(<?= $row['id_menu'] ?>)">
                                <i class="bi bi-power"></i> ปิดเมนู
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center mt-4 text-muted small">
        <i class="bi bi-info-circle me-1"></i> เมื่อเปลี่ยนสถานะสินค้า ระบบจะส่งผลต่อหน้าการสั่งซื้อของลูกค้าทันที
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function notifyAdmin(menuName) {
    Swal.fire({
        title: 'ยืนยันการแจ้งเตือน?',
        text: "ระบบจะแจ้งผู้จัดการร้านว่า '" + menuName + "' หมดชั่วคราว",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ee5253',
        cancelButtonColor: '#2d3436',
        confirmButtonText: 'แจ้งทันที',
        cancelButtonText: 'ยกเลิก',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'สำเร็จ!',
                text: 'ส่งข้อมูลแจ้งแอดมินเรียบร้อยแล้ว',
                icon: 'success',
                confirmButtonColor: '#ffca28'
            });
        }
    })
}

function toggleProduct(id) {
    // เพิ่มเติม: สามารถเรียก AJAX เพื่อ UPDATE tb_menu SET menu_status = 0 ได้ที่นี่
    console.log('Update status for ID: ' + id);
}
</script>

</body>
</html>