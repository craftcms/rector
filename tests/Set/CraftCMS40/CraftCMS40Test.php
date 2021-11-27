<?php

declare(strict_types=1);

namespace CraftCMS\Upgrader\Tests\Set\CraftCMS40;

use CraftCMS\Upgrader\Set\CraftCMSSetList;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class CraftCMS40Test extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    /**
     * @return \Iterator<SmartFileInfo>
     */
    public function provideData(): \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return CraftCMSSetList::CRAFT_CMS_40;
    }
}
