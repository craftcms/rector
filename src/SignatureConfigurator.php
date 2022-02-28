<?php
declare(strict_types = 1);

namespace craft\rector;

use PHPStan\ShouldNotHappenException;
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
use Throwable;

final class SignatureConfigurator
{
    public static function configure(ContainerConfigurator $containerConfigurator, array $signatures): void
    {
        $propertyTypeConfigs = [];
        $methodReturnTypeConfigs = [];
        $methodParamTypeConfigs = [];

        $unexpectedTypesPath = __DIR__ . '/unexpected-types.txt';
        if (file_exists($unexpectedTypesPath)) {
            unlink($unexpectedTypesPath);
        }

        foreach ($signatures['propertyTypes'] as [$className, $propertyName, $type]) {
            if (class_exists($className)) {
                try {
                    $propertyTypeConfigs[] = new AddPropertyTypeDeclaration($className, $propertyName, self::type($className, $type));
                } catch (ShouldNotHappenException) {
                    file_put_contents($unexpectedTypesPath, sprintf("$className::\$$propertyName (%s)\n", is_array($type) ? implode('|', $type) : $type), FILE_APPEND);
                } catch (Throwable $e) {
                    file_put_contents(__DIR__ . '/error.txt', sprintf("property type\n\n%s\n\n%s", print_r([$className, $propertyName, $type], true), print_r($e, true)));
                    throw $e;
                }
            }
        }

        foreach ($signatures['methodReturnTypes'] as [$className, $method, $returnType]) {
            if (class_exists($className)) {
                try {
                    $methodReturnTypeConfigs[] = new AddReturnTypeDeclaration($className, $method, self::type($className, $returnType));
                } catch (ShouldNotHappenException) {
                    file_put_contents($unexpectedTypesPath, sprintf("$className::$method(): %s\n", is_array($returnType) ? implode('|', $returnType) : $returnType), FILE_APPEND);
                } catch (Throwable $e) {
                    file_put_contents(__DIR__ . '/error.txt', sprintf("method return type\n\n%s\n\n%s", print_r([$className, $method, $returnType], true), print_r($e, true)));
                    throw $e;
                }
            }
        }

        foreach ($signatures['methodParamTypes'] as [$className, $method, $position, $paramType]) {
            if (class_exists($className)) {
                try {
                    $methodParamTypeConfigs[] = new AddParamTypeDeclaration($className, $method, $position, self::type($className, $paramType));
                } catch (ShouldNotHappenException) {
                    file_put_contents($unexpectedTypesPath, sprintf("$className::$method(%s%s \$x)\n", str_repeat(', ', $position), is_array($paramType) ? implode('|', $paramType) : $paramType), FILE_APPEND);
                } catch (Throwable $e) {
                    file_put_contents(__DIR__ . '/error.txt', sprintf("method param type\n\n%s\n\n%s", print_r([$className, $method, $position, $paramType], true), print_r($e, true)));
                    throw $e;
                }
            }
        }

        $services = $containerConfigurator->services();
        $services->set(AddPropertyTypeDeclarationRector::class)->configure($propertyTypeConfigs);
        $services->set(AddReturnTypeDeclarationRector::class)->configure($methodReturnTypeConfigs);
        $services->set(AddParamTypeDeclarationRector::class)->configure($methodParamTypeConfigs);
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
