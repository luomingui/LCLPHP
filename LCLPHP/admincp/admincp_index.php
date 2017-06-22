<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：首页
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
@include_once LCL_ROOT . './lcl_version.php';
libfile('function/uc');
$serverinfo = PHP_OS . ' / PHP v' . PHP_VERSION;
$serverinfo .= @ini_get('safe_mode') ? ' Safe Mode' : NULL;
$serversoft = $_SERVER['SERVER_SOFTWARE'];
$dbversion = helper_dbtool::dbversion();
$dbsize = helper_dbtool::dbsize();
$dbsize = $dbsize ? sizecount($dbsize) : 'unknown';
if (@ini_get('file_uploads')) {
    $fileupload = ini_get('upload_max_filesize');
} else {
    $fileupload = '<font color="red">' . $lang['no'] . '</font>';
}
include cptemplate('index');
?>