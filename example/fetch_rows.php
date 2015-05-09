<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Google\SpreadSheets;

$user = require __DIR__ .'/user.php';

$sheetKey = '1baBa_WjZHg1diTWB0oGWaCfGN_igt1rXGKElYKrzi8Q';
$worksheetId = 'od6';

$monsters = SpreadSheets::login($user)->getReader()
    ->select(['id'])
    ->from($sheetKey, $worksheetId)
    ->exec()
    ->fetchAll();

var_dump($monsters);

