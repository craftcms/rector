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
 * @status WIP
 * @see https://github.com/craftcms/cms/blob/4.0/CHANGELOG.md#changed
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $nullableStringType = new UnionType([new StringType(), new NullType()]);

    // Relational fields’ $allowLargeThumbsView properties must now have a bool type declaration.
    $services->set(AddPropertyTypeDeclarationRector::class)
        ->configure([
            new AddPropertyTypeDeclaration('craft\fields\BaseRelationField', 'allowLargeThumbsView', new BooleanType()),
            new AddPropertyTypeDeclaration('craft\fields\BaseRelationField', 'inputJsClass', $nullableStringType),
            new AddPropertyTypeDeclaration('craft\fields\BaseRelationField', 'inputTemplate', new StringType()),
            new AddPropertyTypeDeclaration('craft\fields\BaseRelationField', 'settingsTemplate', new StringType()),
            new AddPropertyTypeDeclaration('craft\fields\BaseRelationField', 'sortable', new BooleanType()),
        ]);
};
