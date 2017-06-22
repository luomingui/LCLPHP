<?php

define('IN_ADMINCP', TRUE);
define('ADMINSCRIPT', basename(__FILE__));
define('CURSCRIPT', 'admin');
define('TEMPLATEID', 'admincp');

require './class/class_core.php';
require './function/function_admincp.php';
require './function/function_cache.php';

$lcl = C::app();
$lcl->init();

$admincp = new lcl_admincp();
$admincp->core = & $lcl;
$admincp->init();


$admincp_actions_founder = array('templates', 'db', 'founder', 'patch');

$admincp_actions_normal = array('login', 'main', 'frame', 'index', 'misc', 'setting', 'ec', 'contestants', 'logout', 'password',
    'expscore', 'importscore', 'tools', 'testdata', 'statuser', 'statteam', 'activities', 'logs', 'team', 'founder', 'news',
    'newscategry', 'district', 'nopgoodscategory', 'nopgoods', 'nopbrand', 'members', 'nopgoodsattribute', 'nopgoodstype', 'member',
    'usergroup', 'nopspec', 'activityactivity', 'activity', 'activityattachment', 'activitymember');

$action = preg_replace('/[^\[A-Za-z0-9_\]]/', '', getgpc('action'));
$operation = preg_replace('/[^\[A-Za-z0-9_\]]/', '', getgpc('operation'));
$do = preg_replace('/[^\[A-Za-z0-9_\]]/', '', getgpc('do'));
$frames = preg_replace('/[^\[A-Za-z0-9_\]]/', '', getgpc('frames'));

$action = empty($action) ? 'frame' : $action;
lang('admincp');
$lang = & $_G['lang']['admincp'];
$perpage = empty($_GET['perpage']) ? 0 : intval($_GET['perpage']);
if (!in_array($perpage, array(10, 20, 50, 100)))
    $perpage = 10;
$page = max(1, intval(getgpc('page')));
$start = ($page - 1) * $perpage;


if (empty($action) || $frames != null) {
    $admincp->show_admincp_main();
} elseif ($action == 'logout') {
    $admincp->do_admin_logout();
    dheader("Location: ./admin.php");
} elseif (in_array($action, $admincp_actions_normal) || ($admincp->isfounder && in_array($action, $admincp_actions_founder))) {
    if ($admincp->allow($action, $operation, $do) || $action == 'password' || $action == 'frame' || $action == 'index') {
        require $admincp->admincpfile($action);
    } else {
        cpmsg('action_noaccess_' . $action, '', 'error');
    }
} else {
    cpmsg('action_noaccess_' . $action, '', 'error');
}
?>