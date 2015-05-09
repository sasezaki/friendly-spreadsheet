<?php

require_once __DIR__ . '/../vendor/autoload.php';

if (!isset($argv['1'])) {
    echo "please specify your google spreadsheets key as an argument.\n";
    exit;
}

use Google\SpreadSheets;

$user = require __DIR__ . '/user.php';
$sheetKey = $argv['1'];

$worksheets = SpreadSheets::login($user)->getDocuments($sheetKey)->all();

foreach ($worksheets as $k => $w) {
    echo sprintf("%s => %s\n", $k, $w);
}

