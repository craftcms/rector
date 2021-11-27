<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * @see https://github.com/craftcms/cms/blob/4.0/CHANGELOG.md#changed
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/craftcms-40/elements.php');
    $containerConfigurator->import(__DIR__ . '/craftcms-40/plugins.php');
    $containerConfigurator->import(__DIR__ . '/craftcms-40/fields.php');
};
