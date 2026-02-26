<?php

function validateTicketInput(array $betTypes, string $type, string $number, float $amount): array
{
    $errors = [];

    if (!isset($betTypes[$type])) {
        $errors[] = 'ประเภทหวยไม่ถูกต้อง';
    }

    if (!ctype_digit($number)) {
        $errors[] = 'เลขที่คีย์ต้องเป็นตัวเลขเท่านั้น';
    } elseif (isset($betTypes[$type]) && strlen($number) !== $betTypes[$type]['digits']) {
        $errors[] = 'จำนวนหลักของเลขไม่ตรงกับประเภทที่เลือก';
    }

    if ($amount <= 0) {
        $errors[] = 'จำนวนเงินต้องมากกว่า 0';
    }

    return $errors;
}

function validateResultInput(string $result3, string $result2): array
{
    $errors = [];

    if (!preg_match('/^\d{3}$/', $result3)) {
        $errors[] = 'ผลรางวัล 3 ตัวบน ต้องเป็นเลข 3 หลัก';
    }

    if (!preg_match('/^\d{2}$/', $result2)) {
        $errors[] = 'ผลรางวัล 2 ตัวล่าง ต้องเป็นเลข 2 หลัก';
    }

    return $errors;
}

function isWinningTicket(string $type, string $betNum, string $result3, string $result2): bool
{
    return match ($type) {
        '3top' => $betNum === $result3,
        '3tod' => count_chars($betNum, 1) === count_chars($result3, 1),
        '2top' => $betNum === substr($result3, -2),
        '2bottom' => $betNum === $result2,
        'run_top' => str_contains($result3, $betNum),
        'run_bottom' => str_contains($result2, $betNum),
        default => false,
    };
}

function summarizeTickets(array $tickets): float
{
    return array_reduce($tickets, static fn(float $carry, array $item): float => $carry + (float)$item['amount'], 0.0);
}

function checkResults(array $tickets, array $betTypes, string $result3, string $result2): array
{
    $rows = [];
    $totalBuy = 0;
    $totalWin = 0;

    foreach ($tickets as $ticket) {
        $type = $ticket['type'];
        $amount = (float)$ticket['amount'];
        $win = isWinningTicket($type, $ticket['number'], $result3, $result2);
        $prize = $win ? $amount * $betTypes[$type]['payout'] : 0;

        $rows[] = [
            'ticket' => $ticket,
            'win' => $win,
            'prize' => $prize,
        ];

        $totalBuy += $amount;
        $totalWin += $prize;
    }

    return [
        'result3' => $result3,
        'result2' => $result2,
        'rows' => $rows,
        'totalBuy' => $totalBuy,
        'totalWin' => $totalWin,
        'net' => $totalWin - $totalBuy,
    ];
}
