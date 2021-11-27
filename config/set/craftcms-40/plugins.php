<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;
use PHPStan\Type\VoidType;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $nullableStringType = new UnionType([new StringType(), new NullType()]);

    $services->set(AddPropertyTypeDeclarationRector::class)
        ->configure([
            new AddPropertyTypeDeclaration('craft\base\PluginTrait', 'changelogUrl', $nullableStringType),
            new AddPropertyTypeDeclaration('craft\base\PluginTrait', 'description', $nullableStringType),
            new AddPropertyTypeDeclaration('craft\base\PluginTrait', 'developer', $nullableStringType),
            new AddPropertyTypeDeclaration('craft\base\PluginTrait', 'developerEmail', $nullableStringType),
            new AddPropertyTypeDeclaration('craft\base\PluginTrait', 'developerUrl', $nullableStringType),
            new AddPropertyTypeDeclaration('craft\base\PluginTrait', 'documentationUrl', $nullableStringType),
            new AddPropertyTypeDeclaration('craft\base\PluginTrait', 'downloadUrl', $nullableStringType),
            new AddPropertyTypeDeclaration('craft\base\PluginTrait', 'edition', new StringType()),
            new AddPropertyTypeDeclaration('craft\base\PluginTrait', 'hasCpSection', new BooleanType()),
            new AddPropertyTypeDeclaration('craft\base\PluginTrait', 'hasCpSettings', new BooleanType()),
            new AddPropertyTypeDeclaration('craft\base\PluginTrait', 'isInstalled', new BooleanType()),
            new AddPropertyTypeDeclaration('craft\base\PluginTrait', 'minVersionRequired', new StringType()),
            new AddPropertyTypeDeclaration('craft\base\PluginTrait', 'name', $nullableStringType),
            new AddPropertyTypeDeclaration('craft\base\PluginTrait', 'packageName', $nullableStringType),
            new AddPropertyTypeDeclaration('craft\base\PluginTrait', 'schemaVersion', new StringType()),
            new AddPropertyTypeDeclaration('craft\base\PluginTrait', 'sourceLanguage', new StringType()),
            new AddPropertyTypeDeclaration('craft\base\PluginTrait', 't9nCategory', $nullableStringType),
        ]);

    $nullableModelType = new UnionType([new ObjectType('\craft\base\Model'), new NullType()]);

    $arrayType = new ArrayType(new MixedType(), new MixedType());
    $nullableArrayType = new UnionType([$arrayType, new NullType()]);

    $services->set(AddReturnTypeDeclarationRector::class)
        ->configure([
            new AddReturnTypeDeclaration('craft\base\Plugin', 'afterSaveSettings', new VoidType()),
            new AddReturnTypeDeclaration('craft\base\Plugin', 'createSettingsModel', $nullableModelType),
            new AddReturnTypeDeclaration('craft\base\Plugin', 'getCpNavItem', $nullableArrayType),
            new AddReturnTypeDeclaration('craft\base\Plugin', 'settingsHtml', $nullableStringType),
        ]);

    $services->set(RenameMethodRector::class)
        ->configure([
            new MethodCallRename('craft\services\Plugins', 'doesPluginRequireDatabaseUpdate', 'isPluginUpdatePending'),
        ]);

    $services->set(RenameClassConstFetchRector::class)
        ->configure([
            new RenameClassAndConstFetch('craft\services\Plugins', 'CONFIG_PLUGINS_KEY', 'craft\services\ProjectConfig', 'PATH_PLUGINS'),
        ]);
};
