<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Google\SpreadSheets;

$user = [
    'user' => '',
    'password' => '',
];

$sheetKey = '';
$worksheetId = '';

$cli = SpreadSheets::login($user)->getReader();
$monsters = $cli->select(['id', 'name'])->from($sheetKey, $worksheetId)->all();

