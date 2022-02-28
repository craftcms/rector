<?php

declare(strict_types = 1);

use craft\rector\SignatureConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function(ContainerConfigurator $containerConfigurator): void {
    // Property/method signatures
    SignatureConfigurator::configure($containerConfigurator, 'craft-commerce-40');
};
