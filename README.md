# FriendlySpreadSheet
simple google spreadsheet client

## develop env
 - mac os x yosemite
 - php55

## usage

[examples](https://github.com/mojibakeo/spreadsheets/tree/master/example)

- install
```
composer require mojibakeo/friendly-spreadsheet dev-master
```

- fetch rows

```php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use FriendlySpreadSheet\FriendlySpreadSheet;

$config = require __DIR__ . '/config.php';

$monsters = FriendlySpreadSheet::auth($config)->createReaderClient()
    ->select(['*'])
    ->from('test', 'モンスター一覧')
    ->where('cost > 20')
    ->orderBy('rare', 'ASC')
    ->setMaxResults(2)
    ->exec()
    ->fetchAll();

var_dump($monsters);

```
