<?php

declare(strict_types = 1);

use craft\rector\SignatureConfigurator;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * @see https://github.com/craftcms/cms/blob/4.0/CHANGELOG.md#changed
 */
return static function(ContainerConfigurator $containerConfigurator): void {
    // Load BaseYii = Craft as they aren't covered by autoloading
//    $cmsPath = getcwd() . '/vendor/craftcms/cms';
//    require $cmsPath . '/lib/yii2/Yii.php';
//    require $cmsPath . '/src/Craft.php';

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
    SignatureConfigurator::configure($containerConfigurator, $signatures);
};
