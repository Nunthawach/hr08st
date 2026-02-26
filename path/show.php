<?php
?>
<div class="card shadow-sm mt-4">
    <div class="card-header">ผลการตรวจรางวัล (3 บน: <?= htmlspecialchars($checkResult['result3']) ?> / 2 ล่าง: <?= htmlspecialchars($checkResult['result2']) ?>)</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>รายการ</th>
                    <th>เลข</th>
                    <th>สถานะ</th>
                    <th>เงินถูกรางวัล</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($checkResult['rows'] as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($betTypes[$row['ticket']['type']]['label']) ?></td>
                        <td><?= htmlspecialchars($row['ticket']['number']) ?></td>
                        <td>
                            <?php if ($row['win']): ?>
                                <span class="badge text-bg-success">ถูกรางวัล</span>
                            <?php else: ?>
                                <span class="badge text-bg-secondary">ไม่ถูก</span>
                            <?php endif; ?>
                        </td>
                        <td><?= number_format((float) $row['prize'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="alert alert-info mb-0">
            ยอดซื้อรวม: <strong><?= $summary['totalBuy'] ?></strong> บาท |
            ยอดถูกรวม: <strong><?= $summary['totalWin'] ?></strong> บาท |
            สุทธิ: <strong><?= $summary['net'] ?></strong> บาท
        </div>
    </div>
</div>
