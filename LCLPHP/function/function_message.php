<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：前台提示信息
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL')) {
    exit('Access Denied');
}

function dshowmessage($message, $url = '', $type = '') {
    global $_G;

    $message = lang('default', $message);
    switch ($type) {
        case 'download':
        case 'succeed': $classname = 'infotitle2';
            break;
        case 'error': $classname = 'infotitle3';
            break;
        case 'loadingform': case 'loading': $classname = 'infotitle1';
            break;
        default: $classname = 'marginbot normal';
            break;
    }
    $message_redirect = '如果您的浏览器没有自动跳转，请点击这里';
    if (strlen($url) <= 0) {
        $url = $_SERVER['HTTP_REFERER'];
    }
    include template('default/common/showmessage');
    dexit();
}

?>