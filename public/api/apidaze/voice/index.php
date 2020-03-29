<?php

// In
error_log(__FILE__.':'.__LINE__.':REQUEST = '.print_r($_REQUEST, true));

// Init
require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/private/init.php');

// Set
$h = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] != '' ? 'https://' . $_SERVER['HTTP_HOST'] : '');
$p = $h . '/audio/ivr/hello.wav';
$x = new ApiDazeXML();
$x->ringback()
  ->wait(1)
  ->answer()
  ->playback($p)
  ->doc();

// Out
error_log(__FILE__.':'.__LINE__.':x = '.print_r($x, true));
$x->output();

?>