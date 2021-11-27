<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;
use PHPStan\Type\VoidType;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $nullableStringType = new UnionType([new StringType(), new NullType()]);

    $arrayType = new ArrayType(new MixedType(), new MixedType());
    $nullableArrayType = new UnionType([$arrayType, new NullType()]);

    // Relational fields’ $allowLargeThumbsView properties must now have a bool type declaration.
    $services->set(AddPropertyTypeDeclarationRector::class)
        ->configure([
            new AddPropertyTypeDeclaration('craft\fields\BaseRelationField', 'allowLargeThumbsView', new BooleanType()),
            new AddPropertyTypeDeclaration('craft\fields\BaseRelationField', 'inputJsClass', $nullableStringType),
            new AddPropertyTypeDeclaration('craft\fields\BaseRelationField', 'inputTemplate', new StringType()),
            new AddPropertyTypeDeclaration('craft\fields\BaseRelationField', 'settingsTemplate', new StringType()),
            new AddPropertyTypeDeclaration('craft\fields\BaseRelationField', 'sortable', new BooleanType()),
        ]);

    $services->set(AddReturnTypeDeclarationRector::class)
        ->configure([
            new AddReturnTypeDeclaration('craft\base\FieldInterface', 'afterElementDelete', new VoidType()),
            new AddReturnTypeDeclaration('craft\base\FieldInterface', 'afterElementPropagate', new VoidType()),
            new AddReturnTypeDeclaration('craft\base\FieldInterface', 'afterElementRestore', new VoidType()),
            new AddReturnTypeDeclaration('craft\base\FieldInterface', 'afterElementSave', new VoidType()),
            new AddReturnTypeDeclaration('craft\base\FieldInterface', 'modifyElementIndexQuery', new VoidType()),
            new AddReturnTypeDeclaration('craft\base\FieldInterface', 'modifyElementsQuery', new VoidType()),
            new AddReturnTypeDeclaration('craft\base\EagerLoadingFieldInterface', 'getEagerLoadingGqlConditions', $nullableArrayType),
            new AddReturnTypeDeclaration('craft\fieldlayoutelements\BaseField', 'inputHtml', $nullableStringType),
            new AddReturnTypeDeclaration('craft\base\FieldLayoutElementInterface', 'settingsHtml', $nullableStringType),
        ]);
};
