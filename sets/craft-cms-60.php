<?php

declare(strict_types=1);

use craft\rector\SignatureConfigurator;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function(RectorConfig $rectorConfig): void {
    // Class renames
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'craft\enums\Edition' => 'CraftCms\Cms\Edition',
    ]);

    // Property/method signatures
    SignatureConfigurator::configure($rectorConfig, 'craft-cms-60');
};
