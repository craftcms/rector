<?php

declare(strict_types=1);

namespace craft\rector\set;

use Rector\Set\Contract\SetListInterface;

final class SetList implements SetListInterface
{
    /**
     * @var string
     */
    public const CRAFT_CMS_40 = __DIR__ . '/../../config/set/craftcms-40.php';
}
