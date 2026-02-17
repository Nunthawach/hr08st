<?php
session_start();

require_once __DIR__ . '/src/LotteryService.php';
$betTypes = require __DIR__ . '/config/lottery.php';

if (!isset($_SESSION['tickets'])) {
    $_SESSION['tickets'] = [];
}

$errors = [];
$notices = [];
$checkResult = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_ticket') {
        $type = $_POST['type'] ?? '';
        $number = trim($_POST['number'] ?? '');
        $amount = (float)($_POST['amount'] ?? 0);

        $errors = validateTicketInput($betTypes, $type, $number, $amount);

        if (!$errors) {
            $_SESSION['tickets'][] = [
                'id' => uniqid('bet_', true),
                'type' => $type,
                'number' => $number,
                'amount' => $amount,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $notices[] = 'บันทึกรายการแทงเรียบร้อย';
        }
    }

    if ($action === 'clear_tickets') {
        $_SESSION['tickets'] = [];
        $notices[] = 'ล้างโพยทั้งหมดแล้ว';
    }

    if ($action === 'check_result') {
        $result3 = trim($_POST['result_3top'] ?? '');
        $result2 = trim($_POST['result_2bottom'] ?? '');

        $errors = validateResultInput($result3, $result2);

        if (!$errors) {
            $checkResult = checkResults($_SESSION['tickets'], $betTypes, $result3, $result2);
        }
    }
}

$tickets = $_SESSION['tickets'];
$totalBuy = summarizeTickets($tickets);

require __DIR__ . '/templates/home.php';
