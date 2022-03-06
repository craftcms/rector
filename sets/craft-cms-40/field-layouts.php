<?php

declare(strict_types=1);

use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameClassConstFetch;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function(ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(RenameClassRector::class)
        ->configure([
            'craft\fieldlayoutelements\StandardField' => 'craft\fieldlayoutelements\BaseNativeField',
            'craft\fieldlayoutelements\StandardTextField' => 'craft\fieldlayoutelements\TextField',
        ]);

    $services->set(RenameClassConstFetchRector::class)
        ->configure([
            new RenameClassConstFetch('craft\models\FieldLayout', 'EVENT_DEFINE_STANDARD_FIELDS', 'EVENT_DEFINE_NATIVE_FIELDS'),
        ]);

    $services->set(RenameMethodRector::class)
        ->configure([
            new MethodCallRename('craft\behaviors\FieldLayoutBehavior', 'getFields', 'getCustomFields'),
            new MethodCallRename('craft\models\FieldLayout', 'getAvailableStandardFields', 'getAvailableNativeFields'),
            new MethodCallRename('craft\models\FieldLayout', 'getFields', 'getCustomFields'),
        ]);
};
