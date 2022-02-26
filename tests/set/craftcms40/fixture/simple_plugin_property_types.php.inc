<?php

use craft\base\Plugin;

final class SimplePluginPropertyTypes extends Plugin
{
    public $hasCpSettings = true;

    public function afterSaveSettings()
    {
    }
}

?>
-----
<?php

use craft\base\Plugin;

final class SimplePluginPropertyTypes extends Plugin
{
    public bool $hasCpSettings = true;

    public function afterSaveSettings(): void
    {
    }
}

?>
