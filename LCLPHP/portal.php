<?php


define('CURSCRIPT', 'portal');

require './class/class_core.php';
$lcl = C::app();
$lcl->init();


$mod = preg_replace('/[^\[A-Za-z0-9_\]]/', '', getgpc('mod'));
$op = preg_replace('/[^\[A-Za-z0-9_\]]/', '', getgpc('op'));
$do = preg_replace('/[^\[A-Za-z0-9_\]]/', '', getgpc('do'));

lang('default');
$lang = & $_G['lang']['default'];
$page = max(1, intval(getgpc('page')));
$uid = $_G['uid'];

if (!in_array($mod, array('article', 'newsinfo'))) {
    $mod = 'index';
}

define('CURMODULE', $mod);


require_once './module/portal/portal_' . $mod . '.php';
?>