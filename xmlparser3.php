<?php

ini_set('max_execution_time', 600); //this time could be increased because server power
ini_set('display_errors', '1');

$start = microtime(true);

require_once __DIR__ . '/Parsec/ClassLoader.php';

$loader = new \Composer\Autoload\ClassLoader();
$loader->add('Parsec', __DIR__);
$loader->register();

$app = new \Parsec\Parsec();
$app->run();

$end = microtime(true);

echo "\ntime spent: ".($end - $start)."\n";
//for 2 millions users it takes 75 seconds