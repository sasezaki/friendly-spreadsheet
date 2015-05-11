<?php

require_once __DIR__ . '/../vendor/autoload.php';

use FriendlySpreadSheet\FriendlySpreadSheet;

$config = require __DIR__ . '/config.php';

FriendlySpreadSheet::auth($config)->createWriterClient()
    ->to('test', 'monsters')
    ->insert([
        'id' => 10,
        'name' => 'foo',
        'cost' => 400,
        'rare' => 340,
        'exp' => 23100,
    ]);

