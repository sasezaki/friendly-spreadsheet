<?php

require_once __DIR__ . '/../vendor/autoload.php';

use FriendlySpreadSheet\FriendlySpreadSheet;

$config = require __DIR__ . '/config.php';

$worksheets = FriendlySpreadSheet::auth($config)->listWorksheet('test');

var_dump($worksheets);
