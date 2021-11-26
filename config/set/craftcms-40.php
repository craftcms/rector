<?php

declare(strict_types=1);

use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(AddPropertyTypeDeclarationRector::class)
        ->configure([
            new AddPropertyTypeDeclaration(
                'craft\base\PluginTrait',
                'hasCpSettings',
                new \PHPStan\Type\BooleanType()
            )
        ]);
};
