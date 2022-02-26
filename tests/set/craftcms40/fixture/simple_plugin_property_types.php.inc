<?php

use craft\base\Plugin;

final class SimplePluginPropertyTypes extends Plugin
{
    public $hasCpSettings = true;

    public function afterSaveSettings()
    {
    }

    protected function createSettingsModel()
    {
        return null;
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

    protected function createSettingsModel(): ?\craft\base\Model
    {
        return null;
    }
}

?>
