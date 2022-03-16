<?php

declare(strict_types=1);

use craft\rector\SignatureConfigurator;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameProperty;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function(ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(RenamePropertyRector::class)
        ->configure([
            new RenameProperty('craft\commerce\model\ProductType', 'titleFormat', 'variantTitleFormat'),
        ]);

    $services->set(RenameMethodRector::class)
        ->configure([
            new MethodCallRename('craft\commerce\services\ShippingMethods', 'getAvailableShippingMethods', 'getMatchingShippingMethods'),
        ]);

    // Property/method signatures
    SignatureConfigurator::configure($containerConfigurator, 'craft-commerce-40');
};
