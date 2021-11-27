<?php

declare(strict_types=1);

use PHPStan\Type\BooleanType;
use PHPStan\Type\NullType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * @see https://github.com/craftcms/cms/blob/4.0/CHANGELOG.md#changed
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $nullableStringType = new UnionType([new StringType(), new NullType()]);

    // starts with "Plugins’ $changelogUrl properties must now have a ?string type declaration..."
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
};
