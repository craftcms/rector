<?php
declare(strict_types = 1);

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
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;

final class SignatureConfigurator
{
    public static function configure(ContainerConfigurator $containerConfigurator, string $name): void
    {
        $signatures = require dirname(__DIR__) . "/signatures/$name.php";
        $services = $containerConfigurator->services();

        if (isset($signatures['propertyTypes'])) {
            $propertyTypeConfigs = [];
            foreach ($signatures['propertyTypes'] as [$className, $propertyName, $type]) {
                if (class_exists($className)) {
                    $propertyTypeConfigs[] = new AddPropertyTypeDeclaration($className, $propertyName, self::type($className, $type));
                }
            }
            $services->set(AddPropertyTypeDeclarationRector::class)->configure($propertyTypeConfigs);
        }

        if ($signatures['methodReturnTypes']) {
            $methodReturnTypeConfigs = [];
            foreach ($signatures['methodReturnTypes'] as [$className, $method, $returnType]) {
                if (class_exists($className)) {
                    $methodReturnTypeConfigs[] = new AddReturnTypeDeclaration($className, $method, self::type($className, $returnType));
                }
            }
            $services->set(AddReturnTypeDeclarationRector::class)->configure($methodReturnTypeConfigs);
        }

        if (isset($signatures['methodParamTypes'])) {
            $methodParamTypeConfigs = [];
            foreach ($signatures['methodParamTypes'] as [$className, $method, $position, $paramType]) {
                if (class_exists($className)) {
                    $methodParamTypeConfigs[] = new AddParamTypeDeclaration($className, $method, $position, self::type($className, $paramType));
                }
            }
            $services->set(AddParamTypeDeclarationRector::class)->configure($methodParamTypeConfigs);
        }
    }

    private static function type(string $className, string|array $type): Type
    {
        if (is_array($type)) {
            return self::unionType($className, $type);
        }

        return match ($type) {
            'array' => new ArrayType(new MixedType(), new MixedType()),
            'bool' => new BooleanType(),
            'callable' => new CallableType(),
            'false' => new ConstantBooleanType(false),
            'float' => new FloatType(),
            'int' => new IntegerType(),
            'iterable' => new IterableType(new MixedType(), new MixedType()),
            'mixed' => new MixedType(),
            'null' => new NullType(),
            'object' => new ObjectWithoutClassType(),
            'self' => new ObjectType($className),
            'string' => new StringType(),
            'void' => new VoidType(),
            default => new ObjectType($type),
        };
    }

    private static function unionType(string $className, array $types): UnionType
    {
        // we can't simply return `new UnionType([...])` here because UnionType ony supports nullable types currently
        // copied from https://github.com/rectorphp/rector-symfony/blob/91fd3f3882171c6f0c7e60c44e689e8d7d8ad0a4/config/sets/symfony/symfony6/symfony-return-types.php#L56-L63
        $unionTypeReflectionClass = new ReflectionClass(UnionType::class);

        /** @var UnionType $type */
        $type = $unionTypeReflectionClass->newInstanceWithoutConstructor();

        $privatesAccessor = new PrivatesAccessor();
        $privatesAccessor->setPrivateProperty($type, 'types', array_map(fn(string $type) => self::type($className, $type), $types));

        return $type;
    }
}
