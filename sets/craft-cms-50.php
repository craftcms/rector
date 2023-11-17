<?php

declare(strict_types=1);

use craft\rector\SignatureConfigurator;
use PHPStan\Type\BooleanType;
use Rector\Arguments\Rector\ClassMethod\ArgumentAdderRector;
use Rector\Arguments\Rector\MethodCall\RemoveMethodCallParamRector;
use Rector\Arguments\ValueObject\ArgumentAdder;
use Rector\Arguments\ValueObject\RemoveMethodCallParam;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\Visibility;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Rector\Renaming\ValueObject\RenameClassConstFetch;
use Rector\Renaming\ValueObject\RenameProperty;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use Rector\Visibility\Rector\ClassMethod\ChangeMethodVisibilityRector;
use Rector\Visibility\ValueObject\ChangeMethodVisibility;

return static function(RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RenameMethodRector::class, [
            // todo: not working in plugin
            new MethodCallRename('yii\base\Application', 'getSections', 'getEntries'),
            new MethodCallRename('craft\base\Element', 'getHasCheckeredThumb', 'hasCheckeredThumb'),
            new MethodCallRename('craft\base\Element', 'getHasRoundedThumb', 'hasRoundedThumb'),
            new MethodCallRename('craft\base\Element', 'getThumbAlt', 'thumbAlt'),
            new MethodCallRename('craft\base\Element', 'getThumbUrl', 'thumbUrl'),
            new MethodCallRename('craft\base\Element', 'tableAttributeHtml', 'attributeHtml'),
            new MethodCallRename('craft\base\ElementInterface', 'getTableAttributeHtml', 'getAttributeHtml'),
            new MethodCallRename('craft\base\FieldInterface', 'valueType', 'phpType'),
            new MethodCallRename('craft\base\PreviewableFieldInterface', 'getTableAttributeHtml', 'getPreviewHtml'),
            new MethodCallRename('craft\base\conditions\BaseCondition', 'conditionRuleTypes', 'selectableConditionRules'),
            new MethodCallRename('craft\fields\BaseRelationField', 'tableAttributeHtml', 'previewHtml'),
            new MethodCallRename('craft\web\CpScreenResponseBehavior', 'additionalButtons', 'additionalButtonsHtml'),
            new MethodCallRename('craft\web\CpScreenResponseBehavior', 'content', 'contentHtml'),
            new MethodCallRename('craft\web\CpScreenResponseBehavior', 'contextMenu', 'contextMenuHtml'),
            new MethodCallRename('craft\web\CpScreenResponseBehavior', 'notice', 'noticeHtml'),
            new MethodCallRename('craft\web\CpScreenResponseBehavior', 'pageSidebar', 'pageSidebarHtml'),
            new MethodCallRename('craft\web\CpScreenResponseBehavior', 'sidebar', 'sidebarHtml'),
            new MethodCallRename('craft\web\Response', 'additionalButtons', 'additionalButtonsHtml'),
            new MethodCallRename('craft\web\Response', 'content', 'contentHtml'),
            new MethodCallRename('craft\web\Response', 'contextMenu', 'contextMenuHtml'),
            new MethodCallRename('craft\web\Response', 'notice', 'noticeHtml'),
            new MethodCallRename('craft\web\Response', 'pageSidebar', 'pageSidebarHtml'),
            new MethodCallRename('craft\web\Response', 'sidebar', 'sidebarHtml'),
        ]);

    $rectorConfig
        ->ruleWithConfiguration(ChangeMethodVisibilityRector::class, [
            new ChangeMethodVisibility('craft\base\Element', 'hasCheckeredThumb', Visibility::PROTECTED),
            new ChangeMethodVisibility('craft\base\Element', 'hasRoundedThumb', Visibility::PROTECTED),
            new ChangeMethodVisibility('craft\base\Element', 'thumbAlt', Visibility::PROTECTED),
            new ChangeMethodVisibility('craft\base\Element', 'thumbUrl', Visibility::PROTECTED),
        ]);

    $rectorConfig
        ->ruleWithConfiguration(RenamePropertyRector::class, [
            new RenameProperty('yii\base\Application', 'sections', 'entries'),
            new RenameProperty('craft\events\RegisterConditionRuleTypesEvent', 'conditionRuleTypes', 'conditionRules'),
            new RenameProperty('craft\fields\Matrix', 'minBlocks', 'minEntries'),
            new RenameProperty('craft\fields\Matrix', 'maxBlocks', 'maxEntries'),
            new RenameProperty('craft\web\CpScreenResponseBehavior', 'additionalButtons', 'additionalButtonsHtml'),
            new RenameProperty('craft\web\CpScreenResponseBehavior', 'content', 'contentHtml'),
            new RenameProperty('craft\web\CpScreenResponseBehavior', 'contextMenu', 'contextMenuHtml'),
            new RenameProperty('craft\web\CpScreenResponseBehavior', 'notice', 'noticeHtml'),
            new RenameProperty('craft\web\CpScreenResponseBehavior', 'pageSidebar', 'pageSidebarHtml'),
            new RenameProperty('craft\web\CpScreenResponseBehavior', 'sidebar', 'sidebarHtml'),
            new RenameProperty('craft\web\Response', 'additionalButtons', 'additionalButtonsHtml'),
            new RenameProperty('craft\web\Response', 'content', 'contentHtml'),
            new RenameProperty('craft\web\Response', 'contextMenu', 'contextMenuHtml'),
            new RenameProperty('craft\web\Response', 'notice', 'noticeHtml'),
            new RenameProperty('craft\web\Response', 'pageSidebar', 'pageSidebarHtml'),
            new RenameProperty('craft\web\Response', 'sidebar', 'sidebarHtml'),
        ]);

    $rectorConfig
        ->ruleWithConfiguration(RenameClassRector::class, [
            'craft\events\RegisterConditionRuleTypesEvent' => 'craft\events\RegisterConditionRulesEvent',
            'craft\services\Sections' => 'craft\services\Entries',
            'craft\base\BlockElementInterface' => 'craft\base\NestedElementInterface',
            'craft\events\SetElementTableAttributeHtmlEvent' => 'craft\events\DefineAttributeHtmlEvent',
        ]);

    $rectorConfig
        ->ruleWithConfiguration(RenameClassConstFetchRector::class, [
            new RenameClassConstFetch('craft\base\Element', 'EVENT_SET_TABLE_ATTRIBUTE_HTML', 'EVENT_DEFINE_ATTRIBUTE_HTML'),
            new RenameClassConstFetch('craft\base\conditions\BaseCondition', 'EVENT_REGISTER_CONDITION_RULE_TYPES', 'EVENT_REGISTER_CONDITION_RULES'),
            new RenameClassConstFetch('craft\generator\Command', 'EVENT_REGISTER_GENERATOR_TYPES', 'EVENT_REGISTER_GENERATORS'),
            new RenameClassAndConstFetch('craft\base\Element', 'ATTR_STATUS_MODIFIED', 'craft\enums\AttributeStatus', 'Modified'),
            new RenameClassAndConstFetch('craft\base\Element', 'ATTR_STATUS_OUTDATED', 'craft\enums\AttributeStatus', 'Outdated'),
            new RenameClassAndConstFetch('craft\fields\Matrix', 'PROPAGATION_METHOD_NONE', 'craft\enums\PropagationMethod', 'None'),
            new RenameClassAndConstFetch('craft\fields\Matrix', 'PROPAGATION_METHOD_SITE_GROUP', 'craft\enums\PropagationMethod', 'SiteGroup'),
            new RenameClassAndConstFetch('craft\fields\Matrix', 'PROPAGATION_METHOD_LANGUAGE', 'craft\enums\PropagationMethod', 'Language'),
            new RenameClassAndConstFetch('craft\fields\Matrix', 'PROPAGATION_METHOD_ALL', 'craft\enums\PropagationMethod', 'All'),
            new RenameClassAndConstFetch('craft\fields\Matrix', 'PROPAGATION_METHOD_CUSTOM', 'craft\enums\PropagationMethod', 'Custom'),
            new RenameClassAndConstFetch('craft\helpers\Db', 'GLUE_AND', 'craft\db\QueryParam', 'AND'),
            new RenameClassAndConstFetch('craft\helpers\Db', 'GLUE_OR', 'craft\db\QueryParam', 'OR'),
            new RenameClassAndConstFetch('craft\helpers\Db', 'GLUE_NOT', 'craft\db\QueryParam', 'NOT'),
            new RenameClassAndConstFetch('craft\models\Section', 'PROPAGATION_METHOD_NONE', 'craft\enums\PropagationMethod', 'None'),
            new RenameClassAndConstFetch('craft\models\Section', 'PROPAGATION_METHOD_SITE_GROUP', 'craft\enums\PropagationMethod', 'SiteGroup'),
            new RenameClassAndConstFetch('craft\models\Section', 'PROPAGATION_METHOD_LANGUAGE', 'craft\enums\PropagationMethod', 'Language'),
            new RenameClassAndConstFetch('craft\models\Section', 'PROPAGATION_METHOD_ALL', 'craft\enums\PropagationMethod', 'All'),
            new RenameClassAndConstFetch('craft\models\Section', 'PROPAGATION_METHOD_CUSTOM', 'craft\enums\PropagationMethod', 'Custom'),
        ]);

    $rectorConfig
        ->ruleWithConfiguration(ArgumentAdderRector::class, [
            // todo: make required (no default value)
            new ArgumentAdder('craft\base\ElementInterface', 'indexHtml', 7, 'sortable', false, new BooleanType()),
            // todo: not working in test
            new ArgumentAdder('craft\base\Field', 'inputHtml', 2, 'inline', false, new BooleanType()),
        ]);

    $rectorConfig
        ->ruleWithConfiguration(RemoveMethodCallParamRector::class, [
            new RemoveMethodCallParam('craft\services\Elements', 'duplicateElement', 3),
            new RemoveMethodCallParam('craft\services\ProjectConfig', 'saveModifiedConfigData', 0),
        ]);

    $rectorConfig
        ->ruleWithConfiguration(RenameStaticMethodRector::class, [
            new RenameStaticMethod('craft\helpers\Db', 'extractGlue', 'craft\db\QueryParam', 'extractOperator'),
        ]);

    // todo: remove default values from args that are now required

    // Property/method signatures
    SignatureConfigurator::configure($rectorConfig, 'craft-cms-50');
};
