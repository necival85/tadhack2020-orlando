<?php

// Init
require_once(dirname(dirname(dirname(__DIR__))) . '/private/init.php');

// Set
$r = array("status"=>1);

// Out
error_log(__FILE__.':'.__LINE__.':r = '.print_r($r, true));
json_response($r);

?>