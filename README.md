# hr08st

ระบบคีย์หวยไทย (PHP) สำหรับ:
- คีย์โพยหวยไทยหลายประเภท
- คำนวณราคาซื้อรวม
- ตรวจผลรางวัลและสรุปสุทธิ

## โครงสร้างไฟล์ (แยกครบตาม path)
- `index.php` — controller หลักและ routing action จากฟอร์ม
- `config/lottery.php` — ตั้งค่าประเภทหวย/อัตราจ่าย
- `src/LotteryService.php` — business logic (validate, คำนวณผล, สรุปยอด)
- `templates/home.php` — ส่วนแสดงผลหน้าเว็บ

## โครงสร้างโฟลเดอร์
```text
hr08st/
├── README.md
├── SECURITY.md
├── in.html
├── index.php
├── config/
│   └── lottery.php
├── src/
│   └── LotteryService.php
└── templates/
    └── home.php
```

## การรัน
```bash
php -S 0.0.0.0:8080 -t .
```
แล้วเปิด `http://localhost:8080/index.php`
