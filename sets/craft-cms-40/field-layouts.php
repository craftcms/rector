<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameClassConstFetch;

return static function(RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RenameClassRector::class, [
            'craft\fieldlayoutelements\StandardField' => 'craft\fieldlayoutelements\BaseNativeField',
            'craft\fieldlayoutelements\StandardTextField' => 'craft\fieldlayoutelements\TextField',
        ]);

    $rectorConfig
        ->ruleWithConfiguration(RenameClassConstFetchRector::class, [
            new RenameClassConstFetch('craft\models\FieldLayout', 'EVENT_DEFINE_STANDARD_FIELDS', 'EVENT_DEFINE_NATIVE_FIELDS'),
        ]);

    $rectorConfig
        ->ruleWithConfiguration(RenameMethodRector::class, [
            new MethodCallRename('craft\behaviors\FieldLayoutBehavior', 'getFields', 'getCustomFields'),
            new MethodCallRename('craft\models\FieldLayout', 'getAvailableStandardFields', 'getAvailableNativeFields'),
            new MethodCallRename('craft\models\FieldLayout', 'getFields', 'getCustomFields'),
        ]);
};
