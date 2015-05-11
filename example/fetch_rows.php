<?php

require_once __DIR__ . '/../vendor/autoload.php';

use FriendlySpreadSheet\FriendlySpreadSheet;

$config = require __DIR__ . '/config.php';

$monsters = FriendlySpreadSheet::auth($config)->createReaderClient()
    ->select(['*'])
    ->from('test', 'monsters')
    ->orderBy('cost', 'ASC')
    ->setMaxResults(2)
    ->exec()
    ->fetchAll();

var_dump($monsters);
exit;
