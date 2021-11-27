<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;
use PHPStan\Type\VoidType;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * @status WIP
 * @see https://github.com/craftcms/cms/blob/4.0/CHANGELOG.md#changed
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $nullableStringType = new UnionType([new StringType(), new NullType()]);
    $arrayType = new ArrayType(new MixedType(), new MixedType());

    $fieldLayoutNullableType = new UnionType([
        new ObjectType('craft\models\FieldLayout'),
        new NullType(),
    ]);

    // Element actions’ getConfirmationMessage() methods must now have a ?string return type declaration....
    $services->set(AddReturnTypeDeclarationRector::class)
        ->configure([
            new AddReturnTypeDeclaration('craft\base\Element', 'getConfirmationMessage', $nullableStringType),
            new AddReturnTypeDeclaration('craft\base\Element', 'getMessage', $nullableStringType),
            new AddReturnTypeDeclaration('craft\base\Element', 'getTriggerHtml', $nullableStringType),
            new AddReturnTypeDeclaration('craft\base\Element', '__toString', new StringType()),
            new AddReturnTypeDeclaration('craft\base\Element', 'afterDelete', new VoidType()),
            new AddReturnTypeDeclaration('craft\base\Element', 'afterMoveInStructure', new VoidType()),
            new AddReturnTypeDeclaration('craft\base\Element', 'afterPropagate', new VoidType()),
            new AddReturnTypeDeclaration('craft\base\Element', 'afterRestore', new VoidType()),
            new AddReturnTypeDeclaration('craft\base\Element', 'afterSave', new VoidType()),
            new AddReturnTypeDeclaration('craft\base\Element', 'attributeLabels', $arrayType),
            new AddReturnTypeDeclaration('craft\base\Element', 'getCpEditUrl', $nullableStringType),
            new AddReturnTypeDeclaration('craft\base\Element', 'getFieldLayout', $fieldLayoutNullableType),
            new AddReturnTypeDeclaration('craft\base\Element', 'getRef', $nullableStringType),
            new AddReturnTypeDeclaration('craft\base\Element', 'getStatus', $nullableStringType),
            new AddReturnTypeDeclaration('craft\base\Element', 'getThumbUrl', $nullableStringType),
            new AddReturnTypeDeclaration('craft\base\Element', 'getTitleTranslationDescription', $nullableStringType),
            new AddReturnTypeDeclaration('craft\base\Element', 'getUriFormat', $nullableStringType),
            new AddReturnTypeDeclaration('craft\base\Element', 'prepElementQueryForTableAttribute', new VoidType()),
            new AddReturnTypeDeclaration('craft\base\Element', 'refHandle', $nullableStringType),
        ]);
};
