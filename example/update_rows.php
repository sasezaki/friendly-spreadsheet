<?php

require_once __DIR__ . '/../vendor/autoload.php';

use FriendlySpreadSheet\FriendlySpreadSheet;

$config = require __DIR__ . '/config.php';

FriendlySpreadSheet::auth($config)->createWriterClient()
    ->to('test', 'monsters')
    ->update(['name' => 'だんみつ',], ['id' => 9]);

