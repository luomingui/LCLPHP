<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：UI框架
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
@include_once LCL_ROOT . './lcl_version.php';
require './admincp/admincp_menu.php';

//topmenu
$topmenhtml = "";
foreach ($topmenu as $k => $v) {
    if ($v === '') {
        $v = @array_keys($menu[$k]);
        $v = $menu[$k][$v[0]][1];
    }
    $topmenhtml.=showheader($k, $v);
}
//leftmenu
$leftmenuhtml = "";
foreach ($menu as $k => $v) {
    $leftmenuhtml.=showmenu($k, $v);
}
unset($menu);

include cptemplate('frame');



