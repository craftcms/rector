<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use Rector\Renaming\ValueObject\RenameProperty;

return static function(RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RenameClassRector::class, [
            'craft\events\GetAssetThumbUrlEvent' => 'craft\events\DefineAssetThumbUrlEvent',
            'craft\events\GetAssetUrlEvent' => 'craft\events\DefineAssetUrlEvent',
        ]);

    $rectorConfig
        ->ruleWithConfiguration(RenamePropertyRector::class, [
            new RenameProperty('craft\events\DraftEvent', 'source', 'canonical'),
            new RenameProperty('craft\events\RevisionEvent', 'source', 'canonical'),
        ]);
};
