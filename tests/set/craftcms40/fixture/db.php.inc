<?php

use craft\helpers\Db;

Db::batchInsert($table, $columns, $rows, false);

?>
-----
<?php

use craft\helpers\Db;

Db::batchInsert($table, $columns, $rows);

?>
