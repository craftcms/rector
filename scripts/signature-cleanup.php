<?php

use Symfony\Component\VarExporter\VarExporter;

if (!isset($_SERVER['argv'][1])) {
    error('No signature file name specified.');
}
$path = realpath(dirname(__DIR__) . "/signatures/{$_SERVER['argv'][1]}.php");
if (!$path) {
    error("No signature file found at {$_SERVER['argv'][1]}.");
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
