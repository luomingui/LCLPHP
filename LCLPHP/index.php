<?php

define('CURSCRIPT', 'news');

require './class/class_core.php';
$lcl = C::app();
$lcl->init();


include simpletemplate('index');
?>