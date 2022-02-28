<?php

use Symfony\Component\VarExporter\VarExporter;

if (!isset($_SERVER['argv'][1])) {
    error('No signature path provided.');
}
$path = realpath($_SERVER['argv'][1]);
if (!$path) {
    error("Invalid signature path: {$_SERVER['argv'][1]}");
}

require dirname(__DIR__) . '/vendor/autoload.php';

$signatures = require $path;
$export = VarExporter::export($signatures);
$output = <<<PHP
<?php

return $export;

PHP;

file_put_contents($path, $output);

echo "done\n";
