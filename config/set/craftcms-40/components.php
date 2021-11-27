<?php

declare(strict_types=1);

use PHPStan\Type\NullType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;
use PHPStan\Type\VoidType;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $nullableStringType = new UnionType([new StringType(), new NullType()]);

    $services->set(AddReturnTypeDeclarationRector::class)
        ->configure([
            new AddReturnTypeDeclaration('craft\base\SavableComponentInterface', 'afterDelete', new VoidType()),
            new AddReturnTypeDeclaration('craft\base\SavableComponentInterface', 'afterSave', new VoidType()),
            new AddReturnTypeDeclaration('craft\base\SavableComponentInterface', 'beforeApplyDelete', new VoidType()),
            new AddReturnTypeDeclaration('craft\base\ConfigurableComponentInterface', 'getSettingsHtml', $nullableStringType),
        ]);
};
