<?php

// Classes
foreach (glob(__DIR__ . '/classes/*.php') as $file) {
    require_once($file);
}

// Functions
foreach (glob(__DIR__ . '/functions/*.php') as $file) {
    require_once($file);
}

?>