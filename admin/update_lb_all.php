<!--เสร็จแล้ว-->
<?php
include 'config.php';

// ฟังก์ชันสำหรับอัปโหลดและ Update DB
function updateImage($conn, $file_field, $id_lb) {
    if (isset($_FILES[$file_field]) && $_FILES[$file_field]['error'] == 0) {
        $ext = pathinfo($_FILES[$file_field]['name'], PATHINFO_EXTENSION);
        $new_name = "lb_" . time() . "_" . $id_lb . "." . $ext;
        $path = "img_ad/" . $new_name;

        if (move_uploaded_file($_FILES[$file_field]['tmp_name'], $path)) {
            $sql = "UPDATE tb_logobanner SET name_lb = '$new_name' WHERE id_lb = $id_lb";
            mysqli_query($conn, $sql);
        }
    }
}

// สั่งทำงานทีละ ID
updateImage($conn, 'logo', 1);
updateImage($conn, 'banner1', 2);
updateImage($conn, 'banner2', 3);
updateImage($conn, 'banner3', 4);

header("Location: admin_lb.php?success=1");
?>