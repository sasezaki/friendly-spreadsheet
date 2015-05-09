# Google\SpreadSheets

- 参照と挿入と更新(ただし1行ずつ)できるよ
- 削除はできない
- Google 中の皆様とかアプリケーション名が Google な場合とか困るかもしれないけど思いつかないのでこんな名前空間にしてしまった

## develop env
 - mac os x yosemite
 - php55

## usage

[exaples](https://github.com/mojibakeo/spreadsheets/tree/master/example)

- get rows
```php
<?php

use Google\Spreadsheets;

$user = [
  'user' => 'mojibakeo@gmail.com',
  'password' => 'password',
];
$rows = Spreadsheets::login($user)->getReader()-from('sheet_key', 'worksheet_id')->all();

var_dump($rows);

```

- insert row

```php
<?php

use Google\Spreadsheets;

$user = [
  'user' => 'mojibakeo@gmail.com',
  'password' => 'password',
];
$row = [
  'id' => 100,
  'name' => 'bko',
]
Spreadsheets::login($user)->getWriter()-to('sheet_key', 'worksheet_id')->insert($row);

```

- update row
```php
<?php

use Google\Spreadsheets;

$user = [
  'user' => 'mojibakeo@gmail.com',
  'password' => 'password',
];

Spreadsheets::login($user)
  ->getWriter()
  -to('sheet_key', 'worksheet_id')
  ->update(['name' => 'mojibakeo'], ['id' => 100]);

```
