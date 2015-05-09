<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Google\SpreadSheets;

$user = require __DIR__ .'/user.php';

$sheetKey = '1baBa_WjZHg1diTWB0oGWaCfGN_igt1rXGKElYKrzi8Q';
$worksheetId = 'od6';

$row = [
    'id' => 8,
    'name' => '挿入太郎',
    'cost' => 'プライスレス',
    'rare' => 4,
    'exp' => 3000,
];

SpreadSheets::login($user)->getWriter()
    ->to($sheetKey, $worksheetId)
    ->insert($row);

