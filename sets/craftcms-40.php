<?php

declare(strict_types = 1);

use craft\rector\SignatureConfigurator;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * @see https://github.com/craftcms/cms/blob/4.0/CHANGELOG.md#changed
 */
return static function(ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/craftcms-40/*');

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

    // Property/method signatures
    SignatureConfigurator::configure($containerConfigurator, 'craftcms-40');
};
