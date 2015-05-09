<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Google\SpreadSheets;

$user = [
    'user' => '',
    'password' => '',
];

$reader = SpreadSheets::login($user)->getReader();
$monsters = $reader->select(['id', 'name'])->all();

