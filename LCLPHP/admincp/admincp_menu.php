<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：后台菜单
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

$topmenu = $menu = array();
$topmenu = array(
    'index' => '',
    'global' => '',
    'news' => '',
    'extended' => '',
    'tools' => '',
    'founder' => '',
);
$menu['index'] = array(
    array('menu_home', 'index'),
    array('menu_custommenu_manage', 'misc_custommenu'),
);
$menu['global'] = array(
    array('menu_setting_basic', 'setting_basic'),
    array('menu_setting_datetime', 'setting_datetime'),
    array('menu_setting_optimize', 'setting_memory'),
    array('menu_setting_district', 'district'),
);

$menu['news'] = array(
    array('menu_news_categry', 'newscategry'),
    array('menu_news', 'news'),
);
$menu['extended'] = array(
    array('menu_ec', 'ec_alipay'),
);
$menu['tools'] = array(
    array('menu_tools_updatecaches', 'tools_updatecache'),
    array('menu_tools_fileperms', 'tools_fileperms'),
    array('menu_tools_logs', 'logs'),
    array('menu_misc_cron', 'misc_cron'),
);
$menu['founder'] = array(
    array('menu_founder_perm', 'founder_perm'),
//    array('menu_db', 'db_export'),
//    array('menu_membersplit', 'membersplit_check'),
//    array('menu_postsplit', 'postsplit_manage'),
//    array('menu_threadsplit', 'threadsplit_manage'),
//    array('menu_upgrade', 'upgrade'),
//    array('menu_optimizer', 'optimizer_performance'),
);
if (!isset($GLOBALS['admincp']->perms['all'])) {
    $menunew = $menu;
    foreach ($menu as $topkey => $datas) {
        if ($topkey == 'index') {
            continue;
        }
        $itemexists = 0;
        foreach ($datas as $key => $data) {
            if (array_key_exists($data[1], $GLOBALS['admincp']->perms)) {
                $itemexists = 1;
            } else {
                unset($menunew[$topkey][$key]);
            }
        }
        if (!$itemexists) {
            unset($topmenu[$topkey]);
            unset($menunew[$topkey]);
        }
    }
    $menu = $menunew;
}





