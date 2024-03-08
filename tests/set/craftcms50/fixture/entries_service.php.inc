<?php

/** @var \craft\services\Sections $service */
$service;

/** @var \craft\web\Application $webApp */
$service1 = $webApp->getSections();
$service2 = $webApp->sections;
/** @var \craft\console\Application $consoleApp */
$service3 = $consoleApp->getSections();
$service4 = $consoleApp->sections;

?>
-----
<?php

/** @var \craft\services\Entries $service */
$service;

/** @var \craft\web\Application $webApp */
$service1 = $webApp->getEntries();
$service2 = $webApp->entries;
/** @var \craft\console\Application $consoleApp */
$service3 = $consoleApp->getEntries();
$service4 = $consoleApp->entries;

?>
