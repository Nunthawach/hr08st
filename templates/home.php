<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบคีย์หวยไทย</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h1 class="mb-3">ระบบคีย์หวยไทย</h1>
    <p class="text-muted">คีย์โพยหวย, คำนวณราคาซื้อรวม และตรวจสอบผลรางวัลในหน้าเดียว</p>

    <?php foreach ($errors as $error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endforeach; ?>

    <?php foreach ($notices as $notice): ?>
        <div class="alert alert-success"><?= htmlspecialchars($notice) ?></div>
    <?php endforeach; ?>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header">คีย์รายการแทง</div>
                <div class="card-body">
                    <form method="post" class="row g-3">
                        <input type="hidden" name="action" value="add_ticket">
                        <div class="col-12">
                            <label class="form-label">ประเภท</label>
                            <select name="type" class="form-select" required>
                                <?php foreach ($betTypes as $key => $info): ?>
                                    <option value="<?= $key ?>"><?= htmlspecialchars($info['label']) ?> (จ่าย x<?= $info['payout'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">เลขที่แทง</label>
                            <input type="text" name="number" class="form-control" placeholder="เช่น 123" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">จำนวนเงิน (บาท)</label>
                            <input type="number" name="amount" class="form-control" min="1" step="0.5" required>
                        </div>
                        <div class="col-12 d-grid">
                            <button type="submit" class="btn btn-primary">เพิ่มรายการ</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-header">ตรวจผลรางวัล</div>
                <div class="card-body">
                    <form method="post" class="row g-3">
                        <input type="hidden" name="action" value="check_result">
                        <div class="col-md-6">
                            <label class="form-label">ผล 3 ตัวบน</label>
                            <input type="text" name="result_3top" class="form-control" maxlength="3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ผล 2 ตัวล่าง</label>
                            <input type="text" name="result_2bottom" class="form-control" maxlength="2" required>
                        </div>
                        <div class="col-12 d-grid">
                            <button type="submit" class="btn btn-success">เช็คผลโพยทั้งหมด</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>โพยที่คีย์ไว้</span>
                    <form method="post" onsubmit="return confirm('ยืนยันการล้างโพยทั้งหมด?');">
                        <input type="hidden" name="action" value="clear_tickets">
                        <button class="btn btn-sm btn-outline-danger" type="submit">ล้างโพย</button>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>ประเภท</th>
                                <th>เลข</th>
                                <th>ยอดซื้อ</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$tickets): ?>
                                <tr><td colspan="4" class="text-center text-muted">ยังไม่มีรายการ</td></tr>
                            <?php else: ?>
                                <?php foreach ($tickets as $i => $ticket): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($betTypes[$ticket['type']]['label']) ?></td>
                                        <td><?= htmlspecialchars($ticket['number']) ?></td>
                                        <td><?= number_format((float)$ticket['amount'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">รวมราคาซื้อ</th>
                                <th><?= number_format($totalBuy, 2) ?> บาท</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <?php if ($checkResult): ?>
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
                                        <td><?= number_format($row['prize'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="alert alert-info mb-0">
                            ยอดซื้อรวม: <strong><?= number_format($checkResult['totalBuy'], 2) ?></strong> บาท |
                            ยอดถูกรวม: <strong><?= number_format($checkResult['totalWin'], 2) ?></strong> บาท |
                            สุทธิ: <strong><?= number_format($checkResult['net'], 2) ?></strong> บาท
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
