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
        ]);

//    All components’ attributes() methods must now have an array return type declaration.
//    All components’ behaviors() methods must now have a void return type declaration.
//    All components’ extraFields() methods must now have an array return type declaration.
//    All components’ fields() methods must now have an array return type declaration.
//    All components’ getSettingsHtml() methods must now have a ?string return type declaration.
//    All components’ init() methods must now have a void return type declaration.
//    All components’ rules() methods must now have an array return type declaration.
};
