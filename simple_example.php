<?php

function execCommand( string $cmd ): string {
  $stdout  = fopen( 'php://temporary', 'w+' );
  $stderr  = fopen( 'php://temporary', 'w+' );
  $streams = [
    0 => [ 'pipe', 'r' ],
    1 => $stdout,
    2 => $stderr
  ];

  $proc = proc_open( $cmd, $streams, $pipes );
  if ( ! $proc ) {
    throw new \RuntimeException( 'Unable to launch download process' );
  }

  do {
    /**
     * This is essential part of the code. It suspends the fiber and allows other fibers to run.
     */
    Fiber::suspend();
    $status = proc_get_status( $proc );
  } while ( $status['running'] );

  proc_close( $proc );
  rewind( $stdout );
  $result = stream_get_contents( $stdout );
  fclose( $stdout );
  fclose( $stderr );

  $success = $status['exitcode'] === 0;
  if ( $success ) {
    return $result;
  } else {
    throw new \RuntimeException( 'Unable to perform conversion' );
  }
}

function callApi( string $api ): array {
  $cmd = "php %s";
  $cmd = sprintf( $cmd, $api );

  $result = execCommand( $cmd );

  return json_decode( $result, true );
}

function tryToGetDataFromFiber( Fiber $fiber ): ?array {
  if ( $fiber->isSuspended() ) {
    $fiber->resume();
    if ( $fiber->isTerminated() ) {
      return $fiber->getReturn();
    }
  }

  return null;
}

$start = microtime( true );

$weatherData = $newsData = null;

$weatherFiber = new Fiber( function () {
  return callApi( 'dummy_api/weather.php' );
} );
$weatherFiber->start();

$newsFiber = new Fiber( function () {
  return callApi( 'dummy_api/news.php' );
} );
$newsFiber->start();

while ( ! $newsData || ! $weatherData ) {
  if ( ! $weatherData ) {
    $weatherData = tryToGetDataFromFiber( $weatherFiber );
  }

  if ( ! $newsData ) {
    $newsData    = tryToGetDataFromFiber( $newsFiber );
  }

}


$end           = microtime( true );
$executionTime = $end - $start;

var_dump( $weatherData, $newsData );
echo "Execution time: " . $executionTime . " seconds\n";