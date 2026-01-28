<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการโลโก้ & แบนเนอร์ - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');
        body { background-color: #fffdf0; font-family: 'Kanit', sans-serif; }
        .card-custom { border-radius: 25px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: #ffffff; transition: transform 0.2s; }
        .card-custom:hover { transform: translateY(-5px); }
        .img-preview { height: 160px; width: 100%; object-fit: contain; background: #fafafa; padding: 15px; border: 2px dashed #eee; border-radius: 20px; }
        .btn-pill { border-radius: 50px; font-weight: 600; }
        
        /* ปุ่มย้อนกลับมาตรฐาน (ตำแหน่งและขนาดเดียวกันทุกหน้า) */
        .btn-back-standard {
            width: 140px;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-4 shadow-sm border-start border-primary border-5">
        <h4 class="fw-bold text-primary mb-0">
            <i class="bi bi-image-fill me-2"></i> จัดการรูปภาพและแบนเนอร์
        </h4>
        <a href="index_ad.php" class="btn btn-secondary btn-sm btn-back-standard shadow-sm">
            <i class="bi bi-arrow-left-circle me-1"></i> กลับหน้าหลัก
        </a>
    </div>

    <div class="row g-4">
        <?php 
        $sections = [
            1 => ['title' => 'Logo Shop', 'preview' => 'preview-logo'],
            2 => ['title' => 'Home Banner 1', 'preview' => 'preview-b1'],
            3 => ['title' => 'Home Banner 2', 'preview' => 'preview-b2'], 
            4 => ['title' => 'Home Banner 3', 'preview' => 'preview-b3']
        ];

        foreach ($sections as $id => $info) : 
            $file_name = isset($images[$id]) ? $images[$id] : "";
            $file_path = "img_ad/" . $file_name;
            $is_file_ready = (!empty($file_name) && file_exists($file_path));
            $display_img = $is_file_ready ? $file_path : "img_ad/default.png";
        ?>
        <div class="col-md-6 col-lg-3">
            <div class="card card-custom p-3 text-center h-100 shadow-sm border-0">
                <div class="mb-2">
                    <span class="badge rounded-pill bg-warning text-dark px-3"><?= $info['title'] ?></span>
                </div>
                
                <div class="my-3">
                    <img id="<?= $info['preview'] ?>" src="<?= $display_img ?>?v=<?=time()?>" class="img-preview shadow-sm" style="border: 2px dashed #ffeeba;">
                </div>

                <div class="mb-3">
                    <?php if ($is_file_ready): ?>
                        <small class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> พบไฟล์ในระบบ</small>
                    <?php else: ?>
                        <small class="text-danger fw-bold"><i class="bi bi-x-circle-fill"></i> ยังไม่ได้อัปโหลด</small>
                    <?php endif; ?>
                </div>

                <form action="process_logobanner.php" method="POST" enctype="multipart/form-data" class="mt-auto">
                    <input type="hidden" name="id_lb" value="<?= $id ?>">
                    <div class="text-start mb-2">
                        <label class="small text-muted ms-2">เปลี่ยนรูปภาพ:</label>
                        <input type="file" name="img_file" class="form-control form-control-sm rounded-pill" onchange="previewImage(this, '<?= $info['preview'] ?>')" required>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" name="btn_save" class="btn btn-warning flex-grow-1 btn-sm btn-pill shadow-sm">
                            <i class="bi bi-upload me-1"></i> อัปโหลด
                        </button>
                        <a href="process_logobanner.php?delete_id=<?= $id ?>" class="btn btn-outline-danger btn-sm btn-pill px-3" onclick="return confirm('ยืนยันการลบรูปภาพนี้?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function previewImage(input, targetId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { 
            document.getElementById(targetId).src = e.target.result; 
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>