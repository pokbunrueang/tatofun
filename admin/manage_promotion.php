<!--เสร็จแล้ว-->
<?php
session_start();
// จุดที่ 1: ถอยออกจากโฟลเดอร์ admin ไปหาไฟล์ config.php
// ตรวจสอบให้แน่ใจว่าไฟล์ config.php อยู่ชั้นนอกจริงๆ
require_once '../config.php'; 

// ตรวจสอบสิทธิ์ admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php"); // ปรับ Path ให้ถอยออกไปหาหน้า login.php ที่อยู่ด้านนอก
    exit();
}

// จุดที่ 2: การลบข้อมูลแบบปลอดภัย (SQL Injection Prevention)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); // บังคับให้เป็นตัวเลขเท่านั้น
    
    // (ทางเลือก) ดึงชื่อไฟล์รูปมาลบทิ้งจากโฟลเดอร์ก่อนลบจาก DB
    $res = mysqli_query($conn, "SELECT pro_img FROM tb_promotions WHERE pro_id = $id");
    $data = mysqli_fetch_assoc($res);
    if ($data && $data['pro_img'] != "") {
        @unlink("uploads/promotions/" . $data['pro_img']);
    }

    $sql_delete = "DELETE FROM tb_promotions WHERE pro_id = $id";
    if (mysqli_query($conn, $sql_delete)) {
        header("Location: manage_promotion.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการโปรโมชั่น - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');
        body { font-family: 'Kanit', sans-serif; background-color: #f4f7f6; }
        .promo-img { width: 100px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; }
        .table-card { border-radius: 15px; overflow: hidden; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark"><i class="bi bi-megaphone-fill text-primary me-2"></i>จัดการโปรโมชั่น</h3>
        <div>
            <a href="index_ad.php" class="btn btn-outline-secondary btn-sm me-2">
                <i class="bi bi-arrow-left"></i> กลับหน้าหลัก
            </a>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addPromoModal">
                <i class="bi bi-plus-lg me-1"></i> เพิ่มโปรโมชั่น
            </button>
        </div>
    </div>

    <div class="card shadow-sm border-0 table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">รูปภาพ</th>
                            <th>ชื่อโปรโมชั่น</th>
                            <th>รายละเอียด</th>
                            <th>สถานะ</th>
                            <th class="text-center pe-4">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($conn, "SELECT * FROM tb_promotions ORDER BY pro_id DESC");
                        if (mysqli_num_rows($query) > 0) {
                            while($row = mysqli_fetch_assoc($query)) {
                                // ตรวจสอบว่ามีไฟล์รูปภาพจริงหรือไม่
                                $imgPath = "uploads/promotions/" . $row['pro_img'];
                                $displayImg = (file_exists($imgPath) && !empty($row['pro_img'])) ? $imgPath : 'https://via.placeholder.com/100x60?text=No+Image';
                        ?>
                        <tr>
                            <td class="ps-4">
                                <img src="<?php echo $displayImg; ?>" class="promo-img" alt="promo">
                            </td>
                            <td><span class="fw-semibold"><?php echo htmlspecialchars($row['pro_name']); ?></span></td>
                            <td><p class="text-muted small mb-0 text-truncate" style="max-width: 200px;"><?php echo htmlspecialchars($row['pro_detail']); ?></p></td>
                            <td>
                                <?php if($row['pro_status'] == 'on'): ?>
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success">กำลังใช้งาน</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary">ปิดใช้งาน</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center pe-4">
                                <a href="?delete=<?php echo $row['pro_id']; ?>" 
                                   class="btn btn-outline-danger btn-sm rounded-pill" 
                                   onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบโปรโมชั่นนี้?')">
                                    <i class="bi bi-trash"></i> ลบ
                                </a>
                            </td>
                        </tr>
                        <?php 
                            } 
                        } else {
                            echo '<tr><td colspan="5" class="text-center py-5 text-muted">ไม่พบข้อมูลโปรโมชั่น</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addPromoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="process_promo.php" method="POST" enctype="multipart/form-data">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">เพิ่มโปรโมชั่นใหม่</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อโปรโมชั่น</label>
                        <input type="text" name="p_name" class="form-control" placeholder="เช่น โปรต้อนรับสงกรานต์" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">รายละเอียด</label>
                        <textarea name="p_detail" class="form-control" rows="3" placeholder="ระบุเงื่อนไขหรือรายละเอียด..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">รูปภาพโปรโมชั่น</label>
                        <input type="file" name="p_img" class="form-control" accept="image/*" required>
                        <div class="form-text text-danger">* แนะนำขนาด 800x480 px</div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">สถานะการแสดงผล</label>
                        <select name="p_status" class="form-select">
                            <option value="on">เปิดใช้งานทันที</option>
                            <option value="off">ปิดไว้ก่อน</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" name="save_promo" class="btn btn-primary px-4">บันทึกข้อมูล</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>