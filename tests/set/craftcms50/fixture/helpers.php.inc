<?php

$param = ['and', 'foo', 'bar'];
$glue = \craft\helpers\Db::extractGlue($param);

?>
-----
<?php

$param = ['and', 'foo', 'bar'];
$glue = \craft\db\QueryParam::extractOperator($param);

?>
