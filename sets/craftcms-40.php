<?php

declare(strict_types = 1);

use PHPStan\ShouldNotHappenException;
use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\CallableType;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\IterableType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use PHPStan\Type\VoidType;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\StaticTypeMapper\ValueObject\Type\SelfObjectType;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

if (!function_exists('normalizeType')) {
    function normalizeType(string $className, string|array $type): Type
    {
        if (is_array($type)) {
            return new UnionType(array_map(fn(string $type) => normalizeType($className, $type), $type));
        }

        return match ($type) {
            'array' => new ArrayType(new MixedType(), new MixedType()),
            'bool' => new BooleanType(),
            'callable' => new CallableType(),
            'false' => new ConstantBooleanType(false),
            'int' => new IntegerType(),
            'iterable' => new IterableType(new MixedType(), new MixedType()),
            'mixed' => new MixedType(),
            'null' => new NullType(),
            'object' => new ObjectWithoutClassType(),
            'self' => new SelfObjectType($className),
            'string' => new StringType(),
            'void' => new VoidType(),
            default => new ObjectType($type),
        };
    }
}

/**
 * @see https://github.com/craftcms/cms/blob/4.0/CHANGELOG.md#changed
 */
return static function(ContainerConfigurator $containerConfigurator): void {
    $craft4Dir = __DIR__ . '/craftcms-40';
    $containerConfigurator->import("$craft4Dir/*");

    $arrayType = new ArrayType(new MixedType(), new MixedType());
    $nullableStringType = new UnionType([new StringType(), new NullType()]);

    $services = $containerConfigurator->services();
    $services->set(RenameMethodRector::class)
        ->configure([
            new MethodCallRename('craft\services\Updates', 'getIsCraftDbMigrationNeeded', 'getIsCraftUpdatePending'),
            new MethodCallRename('craft\services\Updates', 'getIsPluginDbUpdateNeeded', 'getIsPluginUpdatePending'),
            new MethodCallRename('craft\gql\directives\FormatDateTime', 'defaultTimezone', 'defaultTimeZone'),
        ]);

    $services->set(RenameClassRector::class)
        ->configure([
            'craft\web\AssetBundle' => 'yii\web\AssetBundle',
        ]);

    // Load the generated signature info
    $signatures = require sprintf('%s/signatures/craftcms-40.php', dirname(__DIR__));

    $propertyTypeConfigs = [];
    $methodReturnTypeConfigs = [];

    $unexpectedTypesPath = __DIR__ . '/unexpected-types.txt';
    if (file_exists($unexpectedTypesPath)) {
        unlink($unexpectedTypesPath);
    }

    foreach ($signatures['properties'] as [$className, $name, $type]) {
        if (class_exists($className)) {
            try {
                $propertyTypeConfigs[] = new AddPropertyTypeDeclaration($className, $name, normalizeType($className, $type));
            } catch (ShouldNotHappenException) {
                file_put_contents($unexpectedTypesPath, sprintf("$className::\$$name (%s)\n", is_array($type) ? implode('|', $type) : $type), FILE_APPEND);
            } catch (Throwable $e) {
                file_put_contents(__DIR__ . '/error.txt', sprintf("property\n\n%s\n\n%s", print_r([$className, $name, $type], true), print_r($e, true)));
                throw $e;
            }
        }
    }

//    foreach ($signatures['methodReturnTypes'] as [$className, $name, $returnType]) {
//        if (class_exists($className)) {
//            try {
//                $methodReturnTypeConfigs[] = new AddReturnTypeDeclaration($className, $name, normalizeType($className, $returnType));
//            } catch (Throwable $e) {
//                file_put_contents(__DIR__ . '/error.txt', sprintf("method return type\n\n%s\n\n%s", print_r([$className, $name, $returnType], true), print_r($e, true)));
//                throw $e;
//            }
//        }
//    }

    $services->set(AddPropertyTypeDeclarationRector::class)->configure($propertyTypeConfigs);
//    $services->set(AddReturnTypeDeclarationRector::class)->configure($methodReturnTypeConfigs);
};
