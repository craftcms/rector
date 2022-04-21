<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;

return static function(RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RenameMethodRector::class, [
            new MethodCallRename('craft\services\ProjectConfig', 'applyYamlChanges', 'applyExternalChanges'),
            new MethodCallRename('craft\services\ProjectConfig', 'getDoesYamlExist', 'getDoesExternalConfigExist'),
            new MethodCallRename('craft\services\ProjectConfig', 'getIsApplyingYamlChanges', 'getIsApplyingExternalChanges'),
            new MethodCallRename('craft\services\ProjectConfig', 'regenerateYamlFromConfig', 'regenerateExternalConfig'),
        ]);

    $rectorConfig
        ->ruleWithConfiguration(RenameClassConstFetchRector::class, [
            new RenameClassAndConstFetch('craft\services\AssetTransforms', 'CONFIG_TRANSFORM_KEY', 'craft\services\ProjectConfig', 'PATH_IMAGE_TRANSFORM'),
            new RenameClassAndConstFetch('craft\services\Categories', 'CONFIG_CATEGORYROUP_KEY', 'craft\services\ProjectConfig', 'PATH_CATEGORY_GROUPS'),
            new RenameClassAndConstFetch('craft\services\Fields', 'CONFIG_FIELDGROUP_KEY', 'craft\services\ProjectConfig', 'PATH_FIELD_GROUPS'),
            new RenameClassAndConstFetch('craft\services\Fields', 'CONFIG_FIELDS_KEY', 'craft\services\ProjectConfig', 'PATH_FIELDS'),
            new RenameClassAndConstFetch('craft\services\Globals', 'CONFIG_GLOBALSETS_KEY', 'craft\services\ProjectConfig', 'PATH_GLOBAL_SETS'),
            new RenameClassAndConstFetch('craft\services\Gql', 'CONFIG_GQL_KEY', 'craft\services\ProjectConfig', 'PATH_GRAPHQL'),
            new RenameClassAndConstFetch('craft\services\Gql', 'CONFIG_GQL_PUBLIC_TOKEN_KEY', 'craft\services\ProjectConfig', 'PATH_GRAPHQL_PUBLIC_TOKEN'),
            new RenameClassAndConstFetch('craft\services\Gql', 'CONFIG_GQL_SCHEMAS_KEY', 'craft\services\ProjectConfig', 'PATH_GRAPHQL_SCHEMAS'),
            new RenameClassAndConstFetch('craft\services\Matrix', 'CONFIG_BLOCKTYPE_KEY', 'craft\services\ProjectConfig', 'PATH_MATRIX_BLOCK_TYPES'),
            new RenameClassAndConstFetch('craft\services\Plugins', 'CONFIG_PLUGINS_KEY', 'craft\services\ProjectConfig', 'PATH_PLUGINS'),
            new RenameClassAndConstFetch('craft\services\Routes', 'CONFIG_ROUTES_KEY', 'craft\services\ProjectConfig', 'PATH_ROUTES'),
            new RenameClassAndConstFetch('craft\services\Sections', 'CONFIG_ENTRYTYPES_KEY', 'craft\services\ProjectConfig', 'PATH_ENTRY_TYPES'),
            new RenameClassAndConstFetch('craft\services\Sections', 'CONFIG_SECTIONS_KEY', 'craft\services\ProjectConfig', 'PATH_PATH_SECTIONS'),
            new RenameClassAndConstFetch('craft\services\Sites', 'CONFIG_SITEGROUP_KEY', 'craft\services\ProjectConfig', 'PATH_SITE_GROUPS'),
            new RenameClassAndConstFetch('craft\services\Sites', 'CONFIG_SITES_KEY', 'craft\services\ProjectConfig', 'PATH_SITES'),
            new RenameClassAndConstFetch('craft\services\Tags', 'CONFIG_TAGGROUP_KEY', 'craft\services\ProjectConfig', 'PATH_TAG_GROUPS'),
            new RenameClassAndConstFetch('craft\services\UserGroups', 'CONFIG_USERPGROUPS_KEY', 'craft\services\ProjectConfig', 'PATH_USER_GROUPS'),
            new RenameClassAndConstFetch('craft\services\Users', 'CONFIG_USERLAYOUT_KEY', 'craft\services\ProjectConfig', 'PATH_USER_FIELD_LAYOUTS'),
            new RenameClassAndConstFetch('craft\services\Users', 'CONFIG_USERS_KEY', 'craft\services\ProjectConfig', 'PATH_USERS'),
            new RenameClassAndConstFetch('craft\services\Volumes', 'CONFIG_VOLUME_KEY', 'craft\services\ProjectConfig', 'PATH_VOLUMES'),
        ]);
};
