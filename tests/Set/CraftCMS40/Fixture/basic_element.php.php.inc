<?php

use craft\base\Element;

final class BasicElement extends Element
{
    public function getFieldLayout()
    {
    }
}

?>
-----
<?php

use craft\base\Element;

final class BasicElement extends Element
{
    public function getFieldLayout(): ?\craft\models\FieldLayout
    {
    }
}

?>
