<?php
?>
<div class="mb-3">
    <h1 class="mb-2">ระบบคีย์หวยไทย</h1>
    <p class="text-muted mb-0">คีย์โพยหวย, คำนวณราคาซื้อรวม และตรวจสอบผลรางวัลในหน้าเดียว</p>
</div>

<?php foreach ($errors as $error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endforeach; ?>

<?php foreach ($notices as $notice): ?>
    <div class="alert alert-success"><?= htmlspecialchars($notice) ?></div>
<?php endforeach; ?>
