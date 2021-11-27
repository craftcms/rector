<?php

declare(strict_types=1);

use PHPStan\Type\IntegerType;
use PHPStan\Type\NullType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $nullableStringType = new UnionType([new StringType(), new NullType()]);
    $nullableIntegerType = new UnionType([new IntegerType(), new NullType()]);

    $services->set(AddReturnTypeDeclarationRector::class)
        ->configure([
            new AddReturnTypeDeclaration('craft\base\WidgetInterface', 'getBodyHtml', $nullableStringType),
            new AddReturnTypeDeclaration('craft\base\WidgetInterface', 'getSubtitle', $nullableStringType),
            new AddReturnTypeDeclaration('craft\base\WidgetInterface', 'getTitle', $nullableStringType),
            new AddReturnTypeDeclaration('craft\base\WidgetInterface', 'icon', $nullableStringType),
            new AddReturnTypeDeclaration('craft\base\WidgetInterface', 'maxColspan', $nullableIntegerType),
        ]);
};
