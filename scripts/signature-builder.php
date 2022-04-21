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

$namespaces = getCliOption('namespaces', 'n');
if ($namespaces) {
    $namespaces = array_map(fn($namespace) => normalizeNamespace($namespace), explode(',', $namespaces));
}

$excludeNamespaces = getCliOption('exclude-namespaces', 'e');
if ($excludeNamespaces) {
    $excludeNamespaces = array_map(fn($namespace) => normalizeNamespace($excludeNamespaces), explode(',', $excludeNamespaces));
}

$filterNamespace = function($class) use ($namespaces, $excludeNamespaces): bool {
    if ($namespaces) {
        $include = false;
        foreach ($namespaces as $namespace) {
            if (str_starts_with($class, $namespace)) {
                $include = true;
                break;
            }
        }
        if (!$include) {
            return false;
        }
    }

    if ($excludeNamespaces) {
        foreach ($excludeNamespaces as $namespace) {
            if (str_starts_with($class, $namespace)) {
                return false;
            }
        }
    }

    return true;
};

$composerConfigPath = "$basePath/composer.json";
if (!file_exists($composerConfigPath)) {
    error("No composer.json file exists at $composerConfigPath.");
}
$composerConfig = json_decode(file_get_contents($composerConfigPath), true);
if (!isset($composerConfig['autoload']['psr-4'])) {
    error("No PSR-4 autoload roots defined in $composerConfigPath.");
}

$sourceRoots = [];
foreach ($composerConfig['autoload']['psr-4'] as $ns => $nsBasePath) {
    $ns = normalizeNamespace($ns);
    $nsBasePath = realpath("$basePath/$nsBasePath");
    if ($nsBasePath) {
        $sourceRoots[$ns] = $nsBasePath;
    }
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

// Make sure it's an optimized autoloader (h/t https://stackoverflow.com/a/46435124/1688568)
$autoloadClass = null;
foreach (get_declared_classes() as $class) {
    if (str_starts_with($class, 'ComposerAutoloaderInit')) {
        $autoloadClass = $class;
        break;
    }
}
if ($autoloadClass === null) {
    error("The autoloader at $autoloadPath isn't optimised.");
}

echo 'Finding source classes … ';

/** @var ClassLoader $classLoader */
$classLoader = $autoloadClass::getLoader();
$srcClasses = [];

foreach ($classLoader->getClassMap() as $class => $file) {
    $file = realpath($file);
    // ignore everything in vendor/
    if (str_starts_with($file, $vendorDir)) {
        continue;
    }
    // make sure it's in one of the source roots
    foreach ($sourceRoots as $namespace => $nsBasePath) {
        if (str_starts_with($file, "$nsBasePath/") && str_starts_with($class, $namespace)) {
            $srcClasses[] = $class;
        }
    }
}

echo "✓\n";

final class SignatureBuilder
{
    /**
     * @var array{propertyTypes: mixed[], methodReturnTypes: mixed[], methodParamTypes: mixed[]}
     */
    private array $signatures;

    /**
     * @var callable
     */
    private $filterNamespace;

    public function __construct(callable $filterNamespaces)
    {
        $this->signatures = [
            'propertyTypes' => [],
            'methodReturnTypes' => [],
            'methodParamTypes' => [],
        ];
        $this->filterNamespace = $filterNamespaces;
    }

    /**
     * @param string[] $classes
     * @return array{propertyTypes: mixed[], methodReturnTypes: mixed[], methodParamTypes: mixed[]}
     */
    public function build(array $classes): array
    {
        asort($classes);
        foreach ($classes as $class) {
            $this->analyzeClass(new ReflectionClass($class));
        }
        return $this->signatures;
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

    private function analyzeClass(ReflectionClass $class): void
    {
        if ($class->isFinal() || !call_user_func($this->filterNamespace, $class->name)) {
            echo "Skipping $class->name\n";
            return;
        }

        echo "Analyzing $class->name … ";

        $parentClass = $class->getParentClass() ?: null;
        $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);
        usort($properties, fn(ReflectionProperty $a, ReflectionProperty$b) => $a->getName() <=> $b->getName());

        foreach ($properties as $property) {
            $declaringClass = $property->getDeclaringClass();
            if ($declaringClass->name !== $class->name) {
                continue;
            }

            $type = $this->serializeType($property->getType(), $class->name);
            if ($type) {
                $parentHasProperty = $parentClass?->hasProperty($property->name);
                $parentProperty = $parentHasProperty ? $parentClass->getProperty($property->name) : null;
                if (!$parentHasProperty || $type !== $this->serializeType($parentProperty->getType(), $parentClass->name)) {
                    $this->signatures['propertyTypes'][] = [$class->name, $property->name, $type];
                }
            }
        }

        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED);
        usort($methods, fn(ReflectionMethod $a, ReflectionMethod $b) => $a->getName() <=> $b->getName());

        foreach ($methods as $method) {
            if ($method->name === '__construct' || $method->getDeclaringClass()->name !== $class->name) {
                continue;
            }

            $parentHasMethod = $parentClass?->hasMethod($method->name);
            $parentMethod = $parentHasMethod ? $parentClass->getMethod($method->name) : null;

            $returnType = $this->serializeType($method->getReturnType(), $class->name);
            if (
                $returnType &&
                (!$parentHasMethod || $returnType !== $this->serializeType($parentMethod->getReturnType(), $parentClass->name))
            ) {
                $this->signatures['methodReturnTypes'][] = [$class->name, $method->name, $returnType];
            }

            $parentParameters = $parentMethod?->getParameters();

            foreach ($method->getParameters() as $pos => $parameter) {
                $type = $this->serializeType($parameter->getType(), $class->name);
                if (
                    $type &&
                    (!isset($parentParameters[$pos]) || $type !== $this->serializeType($parentParameters[$pos]->getType(), $parentClass->name))
                ) {
                    $this->signatures['methodParamTypes'][] = [$class->name, $method->name, $pos, $type];
                }
            }
        }

        echo "✓\n";
    }
}

$signatures = (new SignatureBuilder($filterNamespace))->build($srcClasses);
$export = var_export($signatures, true);
$output = <<<PHP
<?php

return $export;

PHP;

file_put_contents(dirname(__DIR__) . "/signatures/$name.php", $output);

echo "done\n";
