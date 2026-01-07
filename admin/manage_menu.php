<?php
session_start();
include('../config.php'); 

// ดึงข้อมูลเมนูเรียงตาม ID ล่าสุด
$sql = "SELECT * FROM tb_menu ORDER BY id_menu DESC"; 
$result = mysqli_query($conn, $sql); 
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการเมนู - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');
        body { font-family: 'Kanit', sans-serif; background-color: #fffdf0; }
        .menu-card { border-radius: 20px; border: none; overflow: hidden; }
        .header-yellow { background-color: #ffc107; color: #000; padding: 20px; }
        .img-preview { object-fit: cover; border-radius: 12px; border: 2px solid #eee; }
        .upload-zone { font-size: 0.8rem; }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="card menu-card shadow-sm">
        <div class="header-yellow d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold"><i class="bi bi-egg-fried me-2"></i> รายการเมนูอาหาร</h4>
            <a href="add_menu.php" class="btn btn-dark rounded-pill btn-sm px-3 fw-bold">+ เพิ่มเมนูใหม่</a>
        </div>
        
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 25%;">ชื่อเมนู</th>
                            <th style="width: 15%;">ราคา</th>
                            <th style="width: 35%;">จัดการรูปภาพ</th>
                            <th style="width: 25%;" class="text-center">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_array($result)) { ?>
                        <tr>
                            <td class="fw-bold text-dark"><?php echo $row['name_menu']; ?></td>
                            <td><span class="text-primary fw-bold"><?php echo number_format($row['price_menu']); ?></span> บาท</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <?php if($row['img_menu']) { ?>
                                        <img src="img_ad/<?php echo $row['img_menu']; ?>" width="60" height="60" class="img-preview">
                                    <?php } else { ?>
                                        <div class="bg-light text-muted d-flex align-items-center justify-content-center" style="width:60px; height:60px; border-radius:12px; font-size:0.7rem;">No Img</div>
                                    <?php } ?>
                                    
                                    <form action="upload_img_action.php" method="POST" enctype="multipart/form-data" class="upload-zone d-flex gap-1">
                                        <input type="hidden" name="id_menu" value="<?php echo $row['id_menu']; ?>">
                                        <input type="file" name="img_menu" class="form-control form-control-sm" style="max-width: 150px;" required>
                                        <button type="submit" class="btn btn-warning btn-sm" title="อัปโหลด">
                                            <i class="bi bi-upload"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit_menu.php?id=<?php echo $row['id_menu']; ?>" class="btn btn-outline-secondary btn-sm px-3">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="delete_menu.php?id=<?php echo $row['id_menu']; ?>" 
                                       class="btn btn-danger btn-sm px-3" 
                                       onclick="return confirm('ยืนยันการลบเมนู [<?php echo $row['name_menu']; ?>] ?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <a href="index_ad.php" class="btn btn-outline-dark btn-sm rounded-pill mt-3 px-4">← กลับหน้าหลัก Admin</a>
        </div>
    </div>
</div>

</body>
</html>