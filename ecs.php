<?php

declare(strict_types=1);

use craft\ecs\SetList as CraftSetList;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function(ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/sets',
        __DIR__ . '/tests',
        __FILE__,
    ]);

    $ecsConfig->sets([
        CraftSetList::CRAFT_CMS_4,
        \Symplify\EasyCodingStandard\ValueObject\Set\SetList::COMMON,
    ]);
};
