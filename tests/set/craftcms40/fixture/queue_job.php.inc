<?php

use craft\queue\BaseJob;

final class MyJob extends BaseJob
{
    public function execute()
    {
    }

    protected function defaultDescription()
    {
    }
}

?>
-----
<?php

use craft\queue\BaseJob;

final class MyJob extends BaseJob
{
    public function execute(): void
    {
    }

    protected function defaultDescription(): ?string
    {
    }
}

?>
