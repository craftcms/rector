<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/sets',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_80,
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
    ]);

    $rectorConfig->ruleWithConfiguration(StringClassNameToClassConstantRector::class, [
        'craft\*',
    ]);
};
