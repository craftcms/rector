<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameProperty;

return static function(RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RenamePropertyRector::class, [
            new RenameProperty('craft\fields\Assets', 'singleUploadLocationSource', 'restrictedLocationSource'),
            new RenameProperty('craft\fields\Assets', 'singleUploadLocationSubpath', 'restrictedLocationSubpath'),
            new RenameProperty('craft\fields\Assets', 'useSingleFolder', 'restrictLocation'),
        ]);

    $rectorConfig
        ->ruleWithConfiguration(RenameMethodRector::class, [
            new MethodCallRename('craft\fields\BaseRelationField', 'inputSiteId', 'targetSiteId'),
        ]);
};
