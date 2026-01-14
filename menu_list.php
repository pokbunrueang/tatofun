<?php
// ดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT * FROM tb_menu ORDER BY menu_id DESC";
$result = mysqli_query($conn, $sql);

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($result && mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
?>
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card card-menu h-100 shadow-sm border-0">
            <img src="img/<?= htmlspecialchars($row['menu_img']) ?>" class="card-img-top" 
                 alt="<?= htmlspecialchars($row['menu_name']) ?>" 
                 onerror="this.src='https://placehold.co/400x300?text=TatoFun'">
            
            <div class="card-body d-flex flex-column p-3 text-center">
                <h5 class="fw-bold mb-1" style="font-size: 1rem;"><?= htmlspecialchars($row['menu_name']) ?></h5>
                <p class="text-muted small mb-3" style="font-size: 0.75rem;">
                    <?= htmlspecialchars($row['menu_detail']) ?: 'อร่อย ฟิน ทุกคำ' ?>
                </p>
                
                <div class="d-flex justify-content-between align-items-center mt-auto">
                    <span class="price-tag fw-bold">฿<?= number_format($row['menu_price'], 0) ?></span>
                    <a href="cart_action.php?action=add&id=<?= $row['menu_id'] ?>" class="btn btn-add btn-sm px-3 rounded-pill">
                        + สั่งเลย
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php 
    }
} else {
    echo "<div class='col-12 text-center py-5'><p class='text-muted'>ยังไม่มีรายการอาหารในขณะนี้</p></div>";
}
?>