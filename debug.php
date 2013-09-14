<?php

function dprint($s, $should_exit=false)
{
  $save = error_reporting();
  error_reporting(E_ERROR);
  ob_start();
  var_dump($s);
  $s = ob_get_clean();
  error_log($s);
  $lines = debug_backtrace();
  foreach($lines as $line)
  {
    $s = isset($line['file']) ? ($line['file'].":".$line['line']) : '';
    $f = (isset($line['class']) && $line['class'] ? $line['class'] . '::' : '') . $line['function'];
    $s.= " - {$f}";
    error_log($s);
    if(!$should_exit) break;
  }
  if($should_exit) die;
  error_reporting($save);
}
set_error_handler('handle_error');
set_exception_handler(function($exception) {
  dprint($exception,true);
});
register_shutdown_function( function() {
  $error = error_get_last();

  if( $error !== NULL) {
    handle_error($error["type"], $error["file"], $error["line"], $error["message"]);
  }
});

function handle_error($errno, $errstr, $errfile, $errline) {
  $should_exit = !($errno & (E_STRICT|E_WARNING|E_NOTICE));
  $s = sprintf("%d - %s - %s:%s",
    $errno,
    $errstr,
    $errfile,
    $errline
  );
  $md5 = md5($s);
  $s = $md5.":{$s}";
  global $WPR_DEBUG_SKIP;
  foreach($WPR_DEBUG_SKIP as $regex)
  {
    if($regex==$md5) return;
    if(preg_match("/{$regex}/", $errfile)==1) return;
  }
  dprint($s, $should_exit);
}

