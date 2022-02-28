<?php

declare(strict_types = 1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameProperty;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function(ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(RenamePropertyRector::class)
        ->configure([
            new RenameProperty('craft\fields\Assets', 'singleUploadLocationSource', 'restrictedLocationSource'),
            new RenameProperty('craft\fields\Assets', 'singleUploadLocationSubpath', 'restrictedLocationSubpath'),
            new RenameProperty('craft\fields\Assets', 'useSingleFolder', 'restrictLocation'),
        ]);

    $services->set(RenameMethodRector::class)
        ->configure([
            new MethodCallRename('craft\fields\BaseRelationField', 'inputSiteId', 'targetSiteId'),
        ]);
};
