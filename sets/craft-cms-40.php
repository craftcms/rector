<?php

declare(strict_types=1);

use craft\rector\SignatureConfigurator;
use Rector\Arguments\Rector\MethodCall\RemoveMethodCallParamRector;
use Rector\Arguments\ValueObject\RemoveMethodCallParam;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameClassConstFetch;
use Rector\Symfony\Set\TwigSetList;
use Rector\Transform\Rector\MethodCall\MethodCallToPropertyFetchRector;
use Rector\Transform\ValueObject\MethodCallToPropertyFetch;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function(ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/craft-cms-40/*');

    $services = $containerConfigurator->services();
    $services->set(RenameMethodRector::class)
        ->configure([
            new MethodCallRename('craft\base\ApplicationTrait', 'getIsSystemOn', 'getIsLive'),
            new MethodCallRename('craft\base\Model', 'getError', 'getFirstError'),
            new MethodCallRename('craft\gql\directives\FormatDateTime', 'defaultTimezone', 'defaultTimeZone'),
            new MethodCallRename('craft\helpers\ArrayHelper', 'filterByValue', 'where'),
            new MethodCallRename('craft\helpers\ElementHelper', 'createSlug', 'normalizeSlug'),
            new MethodCallRename('craft\helpers\FileHelper', 'removeFile', 'unlink'),
            new MethodCallRename('craft\helpers\UrlHelper', 'getProtocolForTokenizedUrl', 'getSchemeForTokenizedUrl'),
            new MethodCallRename('craft\helpers\UrlHelper', 'urlWithProtocol', 'urlWithScheme'),
            new MethodCallRename('craft\i18n\Locale', 'getName', 'getDisplayName'),
            new MethodCallRename('craft\i18n\Locale', 'getNativeName', 'getDisplayName'),
            new MethodCallRename('craft\services\Assets', 'getCurrentUserTemporaryUploadFolder', 'getUserTemporaryUploadFolder'),
            new MethodCallRename('craft\services\Updates', 'getIsCraftDbMigrationNeeded', 'getIsCraftUpdatePending'),
            new MethodCallRename('craft\services\Updates', 'getIsPluginDbUpdateNeeded', 'getIsPluginUpdatePending'),
        ]);

    $services->set(MethodCallToPropertyFetchRector::class)
        ->configure([
            new MethodCallToPropertyFetch('craft\elements\User', 'getFullName', 'fullName'),
        ]);

    $services->set(RemoveMethodCallParamRector::class)
        ->configure([
            new RemoveMethodCallParam('craft\helpers\Db', 'batchInsert', 3),
            new RemoveMethodCallParam('craft\helpers\Db', 'insert', 2),
            new RemoveMethodCallParam('craft\db\Command', 'batchInsert', 3),
            new RemoveMethodCallParam('craft\db\Command', 'insert', 2),
            new RemoveMethodCallParam('craft\db\Migration', 'batchInsert', 3),
            new RemoveMethodCallParam('craft\db\Migration', 'insert', 2),
        ]);

    $services->set(RenameClassRector::class)
        ->configure([
            'craft\app\web\UrlRule' => 'craft\web\UrlRule',
        ]);

    $services->set(RenameClassConstFetchRector::class)
        ->configure([
            new RenameClassConstFetch('Craft', 'Client', 'Pro'),
            new RenameClassConstFetch('Craft', 'Personal', 'Solo'),
        ]);

    // Property/method signatures
    SignatureConfigurator::configure($containerConfigurator, 'craft-cms-40');

    // Twig 3
    $containerConfigurator->import(TwigSetList::TWIG_UNDERSCORE_TO_NAMESPACE);
};
