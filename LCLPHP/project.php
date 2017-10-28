<?php

define('CURSCRIPT', 'project');

require './class/class_core.php';
$lcl = C::app();
$lcl->init();


include simpletemplate('project');
?>