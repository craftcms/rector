<?php

declare(strict_types = 1);

use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use Rector\Renaming\ValueObject\RenameProperty;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function(ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(RenameClassRector::class)
        ->configure([
            'craft\events\GetAssetThumbUrlEvent' => 'craft\events\DefineAssetThumbUrlEvent',
            'craft\events\GetAssetUrlEvent' => 'craft\events\DefineAssetUrlEvent',
        ]);

    $services->set(RenamePropertyRector::class)
        ->configure([
            new RenameProperty('craft\events\DraftEvent', 'source', 'canonical'),
            new RenameProperty('craft\events\RevisionEvent', 'source', 'canonical'),
        ]);
};
