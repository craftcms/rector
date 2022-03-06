<?php

declare(strict_types=1);

use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameClassConstFetch;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function(ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(RenameClassConstFetchRector::class)
        ->configure([
            new RenameClassConstFetch('craft\services\Assets', 'EVENT_GET_ASSET_THUMB_URL', 'EVENT_DEFINE_THUMB_URL'),
            new RenameClassConstFetch('craft\services\Assets', 'EVENT_GET_ASSET_URL', 'EVENT_DEFINE_ASSET_URL'),
            new RenameClassConstFetch('craft\services\Assets', 'EVENT_GET_THUMB_PATH', 'EVENT_DEFINE_THUMB_PATH'),
        ]);

    $services->set(RenameMethodRector::class)
        ->configure([
            new MethodCallRename('craft\elements\Asset', 'getUri', 'getPath'),
        ]);
};
