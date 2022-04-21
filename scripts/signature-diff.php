<?php

use Composer\Autoload\ClassLoader;

@ini_set('memory_limit', '-1');

function error(string $message): void
{
    echo "$message\n";
    exit(1);
}

if (!isset($_SERVER['argv'][1])) {
    error('No source path provided.');
}
$basePath = realpath($_SERVER['argv'][1]);
if (!$basePath) {
    error("Invalid source path: {$_SERVER['argv'][1]}");
}

if (!isset($_SERVER['argv'][2])) {
    error('No signature file name provided.');
}
$name = $_SERVER['argv'][2];

$signatureFile = dirname(__DIR__) . "/signatures/$name.php";
if (!is_file($signatureFile)) {
    error("no signature file at $signatureFile.");
}

$oldSignatures = require $signatureFile;

/**
 * @return string|string[]|null
 */
function getCliOption(string $name, ?string $alias = null): string|array|null
{
    $pos = array_search("--$name", $_SERVER['argv']) ?: ($alias ? array_search("-$alias", $_SERVER['argv']) : null);
    return $pos && isset($_SERVER['argv'][$pos + 1]) ? $_SERVER['argv'][$pos + 1] : null;
}

function normalizeNamespace(string $namespace): string
{
    return rtrim($namespace, '\\') . '\\';
}

$vendorDir = realpath("$basePath/vendor");
$autoloadPath = "$vendorDir/autoload.php";
if (!file_exists($autoloadPath)) {
    error("No autoloader exists at $autoloadPath.");
}
require_once $autoloadPath;

$autoload = getCliOption('autoload', 'a');
if ($autoload) {
    foreach (explode(',', $autoload) as $path) {
        require_once "$basePath/$path";
    }
}

final class SignatureDiffer
{
    /**
     * @param array{propertyTypes: mixed[], methodReturnTypes: mixed[], methodParamTypes: mixed[]} $oldSignatures
     * @return array{propertyTypes: mixed[], methodReturnTypes: mixed[], methodParamTypes: mixed[]}
     */
    public function diff(array $oldSignatures): array
    {
        return [
            'propertyTypes' => array_values(array_filter($oldSignatures['propertyTypes'], fn($info) => self::includePropertyType(...$info))),
            'methodReturnTypes' => array_values(array_filter($oldSignatures['methodReturnTypes'], fn($info) => self::includeMethodReturnType(...$info))),
            'methodParamTypes' => array_values(array_filter($oldSignatures['methodParamTypes'], fn($info) => self::includeMethodParamType(...$info))),
        ];
    }

    private function includePropertyType(string $className, string $propertyName, string $type): bool
    {
        if (!class_exists($className)) {
            // just in case it was renamed
            return true;
        }
        $class = new ReflectionClass($className);
        if (!$class->hasProperty($propertyName)) {
            return true;
        }
        $oldType = $class->getProperty($propertyName)->getType();
        return $type !== $this->serializeType($oldType, $className);
    }

    private function includeMethodReturnType(string $className, string $method, string $returnType): bool
    {
        if (!class_exists($className)) {
            // just in case it was renamed
            return true;
        }
        $class = new ReflectionClass($className);
        if (!$class->hasMethod($method)) {
            return true;
        }
        $oldReturnType = $class->getMethod($method)->getReturnType();
        return $returnType !== $this->serializeType($oldReturnType, $className);
    }

    private function includeMethodParamType(string $className, string $method, int $position, string $paramType): bool
    {
        if (!class_exists($className)) {
            // just in case it was renamed
            return true;
        }
        $class = new ReflectionClass($className);
        if (!$class->hasMethod($method)) {
            return true;
        }
        $oldParams = $class->getMethod($method)->getParameters();
        return !isset($oldParams[$position]) || $paramType !== $this->serializeType($oldParams[$position]->getType(), $className);
    }

    private function serializeType(?ReflectionType $type, string $className): ?string
    {
        if ($type === null) {
            return null;
        }
        if ($type instanceof ReflectionUnionType) {
            return implode('|', array_map(function(ReflectionNamedType $type) use ($className) {
                $name = $type->getName();
                return $name === 'self' ? $className : $name;
            }, $type->getTypes()));
        }
        // todo:
//        if ($type instanceof ReflectionIntersectionType) {
//            return array_merge(['&'], array_map(fn(ReflectionNamedType $type) => $type->getName(), $type->getTypes()));
//        }
        if ($type instanceof ReflectionNamedType) {
            $name = $type->getName();
            if ($name === 'self') {
                $name = $className;
            }
            if ($name !== 'mixed' && $type->allowsNull()) {
                return "$name|null";
            }
            return $name;
        }
        throw new UnexpectedValueException(sprintf('Unexpected reflection type: %s', get_class($type)));
    }
}

$signatures = (new SignatureDiffer())->diff($oldSignatures);
$export = var_export($signatures, true);
$output = <<<PHP
<?php

return $export;

PHP;

file_put_contents($signatureFile, $output);

echo "done\n";
