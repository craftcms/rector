<?php

declare(strict_types=1);

namespace craft\rector;

use Rector\Set\Contract\SetListInterface;

final class SetList implements SetListInterface
{
    public const CRAFT_CMS_40 = __DIR__ . '/../sets/craft-cms-40.php';
}
