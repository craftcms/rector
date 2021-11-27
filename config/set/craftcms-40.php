<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\VoidType;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * @see https://github.com/craftcms/cms/blob/4.0/CHANGELOG.md#changed
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/craftcms-40/*');

    $arrayType = new ArrayType(new MixedType(), new MixedType());

    $services = $containerConfigurator->services();
    $services->set(RenameMethodRector::class)
        ->configure([
            new MethodCallRename('craft\services\Updates', 'getIsCraftDbMigrationNeeded', 'getIsCraftUpdatePending'),
            new MethodCallRename('craft\services\Updates', 'getIsPluginDbUpdateNeeded', 'getIsPluginUpdatePending'),
        ]);

    $services->set(AddReturnTypeDeclarationRector::class)
        ->configure([
            new AddReturnTypeDeclaration('craft\base\Model', 'attributes', $arrayType),
            new AddReturnTypeDeclaration('craft\base\Model', 'behaviors', $arrayType),
            new AddReturnTypeDeclaration('craft\base\Model', 'extraFields', $arrayType),
            new AddReturnTypeDeclaration('craft\base\Model', 'fields', $arrayType),
            new AddReturnTypeDeclaration('craft\base\Model', 'init', new VoidType()),
            new AddReturnTypeDeclaration('craft\base\Model', 'rules', $arrayType),
        ]);
};
