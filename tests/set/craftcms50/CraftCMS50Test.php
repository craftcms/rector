<?php

declare(strict_types=1);

namespace craft\rector\tests\set\craftcms40;

use craft\rector\SetList;
use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class CraftCMS50Test extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/fixture');
    }

    public function provideConfigFilePath(): string
    {
        return SetList::CRAFT_CMS_50;
    }
}
