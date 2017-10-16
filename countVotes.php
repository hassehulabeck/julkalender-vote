<?php
ini_set('error_reporting', E_ALL & ~E_NOTICE);
spl_autoload_register(function ($class_name) {
    include 'class/' . $class_name . '.php';
});

$vc = new voteCounter;
$vc->count();
?>
