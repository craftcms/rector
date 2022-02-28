<?php

declare(strict_types = 1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function(ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(RenameMethodRector::class)
        ->configure([
            new MethodCallRename('craft\services\Plugins', 'doesPluginRequireDatabaseUpdate', 'isPluginUpdatePending'),
        ]);

    $services->set(RenameClassConstFetchRector::class)
        ->configure([
            new RenameClassAndConstFetch('craft\services\Plugins', 'CONFIG_PLUGINS_KEY', 'craft\services\ProjectConfig', 'PATH_PLUGINS'),
        ]);
};
