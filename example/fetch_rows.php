<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Google\SpreadSheets;

$user = require __DIR__ .'/user.php';

$sheetKey = '';
$worksheetId = '';

$cli = SpreadSheets::login($user)->getReader();
$monsters = $cli->select(['id', 'name'])->from($sheetKey, $worksheetId)->all();

