<?php

declare(strict_types=1);

use craft\rector\SignatureConfigurator;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameProperty;

return static function(RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RenamePropertyRector::class, [
            new RenameProperty('craft\commerce\model\ProductType', 'titleFormat', 'variantTitleFormat'),
        ]);

    $rectorConfig
        ->ruleWithConfiguration(RenameMethodRector::class, [
            new MethodCallRename('craft\commerce\services\ShippingMethods', 'getAvailableShippingMethods', 'getMatchingShippingMethods'),
        ]);

    // Property/method signatures
    SignatureConfigurator::configure($rectorConfig, 'craft-commerce-40');
};
