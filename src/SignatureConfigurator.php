<?php

declare(strict_types=1);

namespace craft\rector;

use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\CallableType;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\FloatType;
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
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use ReflectionClass;
use ReflectionProperty;

final class SignatureConfigurator
{
    /**
     * @var Type[]
     */
    private static array $types = [];

    public static function configure(RectorConfig $rectorConfig, string $name): void
    {
        $signatures = require dirname(__DIR__) . sprintf('/signatures/%s.php', $name);

        if (isset($signatures['propertyTypes'])) {
            $propertyTypeConfigs = [];
            foreach ($signatures['propertyTypes'] as [$className, $propertyName, $type]) {
                if (class_exists($className) || interface_exists($className)) {
                    $propertyTypeConfigs[] = new AddPropertyTypeDeclaration($className, $propertyName, self::type($type));
                }
            }

            $rectorConfig->ruleWithConfiguration(AddPropertyTypeDeclarationRector::class, $propertyTypeConfigs);
        }

        if ($signatures['methodReturnTypes']) {
            $methodReturnTypeConfigs = [];
            foreach ($signatures['methodReturnTypes'] as [$className, $method, $returnType]) {
                if (class_exists($className) || interface_exists($className)) {
                    $methodReturnTypeConfigs[] = new AddReturnTypeDeclaration($className, $method, self::type($returnType));
                }
            }

            $rectorConfig->ruleWithConfiguration(AddReturnTypeDeclarationRector::class, $methodReturnTypeConfigs);
        }

        if (isset($signatures['methodParamTypes'])) {
            $methodParamTypeConfigs = [];
            foreach ($signatures['methodParamTypes'] as [$className, $method, $position, $paramType]) {
                if (class_exists($className) || interface_exists($className)) {
                    $methodParamTypeConfigs[] = new AddParamTypeDeclaration($className, $method, $position, self::type($paramType));
                }
            }

            $rectorConfig->ruleWithConfiguration(AddParamTypeDeclarationRector::class, $methodParamTypeConfigs);
        }
    }

    private static function type(string $type): Type
    {
        if (!isset(self::$types[$type])) {
            self::$types[$type] = self::createType($type);
        }

        return self::$types[$type];
    }

    private static function createType(string $type): Type
    {
        if (str_contains($type, '|')) {
            return self::createUnionType(explode('|', $type));
        }

        return match ($type) {
            'array' => new ArrayType(new MixedType(), new MixedType()),
            'bool' => new BooleanType(),
            'callable' => new CallableType(),
            'false' => new ConstantBooleanType(false),
            'float' => new FloatType(),
            'int' => new IntegerType(),
            'iterable' => new IterableType(new MixedType(), new MixedType()),
            'mixed' => new MixedType(true),
            'null' => new NullType(),
            'object' => new ObjectWithoutClassType(),
            'string' => new StringType(),
            'void' => new VoidType(),
            default => new ObjectType($type),
        };
    }

    /**
     * @param string[] $types
     */
    private static function createUnionType(array $types): UnionType
    {
        $normalizedTypes = array_map(fn(string $type) => self::type($type), $types);

        if (count($types) === 2 && in_array('null', $types)) {
            return new UnionType($normalizedTypes);
        }

        // we can't simply return `new UnionType([...])` here because UnionType ony supports nullable types currently
        // copied from https://github.com/rectorphp/rector-symfony/blob/91fd3f3882171c6f0c7e60c44e689e8d7d8ad0a4/config/sets/symfony/symfony6/symfony-return-types.php#L56-L63
        $unionTypeReflectionClass = new ReflectionClass(UnionType::class);

        /** @var UnionType $type */
        $type = $unionTypeReflectionClass->newInstanceWithoutConstructor();

        // write private property
        $typesReflectionProperty = new ReflectionProperty($type, 'types');
        $typesReflectionProperty->setAccessible(true);
        $typesReflectionProperty->setValue($type, $normalizedTypes);

        return $type;
    }
}
