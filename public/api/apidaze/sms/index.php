<?php

// In
error_log(__FILE__.':'.__LINE__.':REQUEST = '.print_r($_REQUEST, true));

// Init
require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/private/init.php');

// Set
$p = 'Hello, you have reached the COVID-19 Self Help Hotline.';
$x = new ApiDazeXML();
$x->echo($p)->doc();

// Out
error_log(__FILE__.':'.__LINE__.':x = '.print_r($x, true));
$x->output();

?>