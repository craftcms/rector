<?php

use craft\db\Command;
use craft\helpers\Db;

Db::batchInsert($table, $columns, $rows, false, $db);
Db::insert($table, $columns, false, $db);
/** @var Command $command */
$command->batchInsert($table, $columns, $rows, false);
$command->insert($table, $columns, false);

?>
-----
<?php

use craft\db\Command;
use craft\helpers\Db;

Db::batchInsert($table, $columns, $rows, $db);
Db::insert($table, $columns, $db);
/** @var Command $command */
$command->batchInsert($table, $columns, $rows);
$command->insert($table, $columns);

?>
