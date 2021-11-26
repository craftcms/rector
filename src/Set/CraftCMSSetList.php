<?php

declare(strict_types=1);

namespace CraftCMS\Upgrader\Set;

use Rector\Set\Contract\SetListInterface;

final class CraftCMSSetList implements SetListInterface
{
    /**
     * @var string
     */
    public const CRAFT_CMS_40 = __DIR__ . '/../../config/set/craftcms-40.php';
}
