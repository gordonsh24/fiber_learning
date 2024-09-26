<?php

require __DIR__ . "/vendor/autoload.php";

use Amp\Future;
use Amp\Process\Process;

$newsCommand    = "php dummy_api/news.php";
$weatherCommand = "php dummy_api/weather.php";

$newsProcess    = Process::start( $newsCommand );
$weatherProcess = Process::start( $weatherCommand );

$f1 = Amp\async( fn() => json_decode( $newsProcess->getStdout()->read() ), true );
$f2 = Amp\async( fn() => json_decode( $weatherProcess->getStdout()->read() ), true );

echo 'Let start the process';

$start = microtime( true );

$result = Future\await( [ $f1, $f2 ] );

echo "News: \n";
var_dump( $result[0] );

echo "Weather: \n";
var_dump( $result[1] );


$end           = microtime( true );
$executionTime = $end - $start;


echo "Execution time: " . $executionTime . " seconds\n";