<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：表单帮助类
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL')) {
    exit('Access Denied');
}

class helper_form {

    public static function submitcheck($var, $allowget = 0, $seccodecheck = 0, $secqaacheck = 0) {
        if (!getgpc($var)) {
            return FALSE;
        } else {
            global $_G;
            if ($allowget ||
                    ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_GET['formhash']) && $_GET['formhash'] == $_G['formhash'] && empty($_SERVER['HTTP_X_FLASH_VERSION']) && (empty($_SERVER['HTTP_REFERER']) ||
                    preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])))) {
                return TRUE;
            } else {
                if ($_G['config']['debug']) {
                    echo '<br><br><br><br><br><br>submitcheck:' . $var . '<br>';
                    echo 'IsPost:' . $_SERVER['REQUEST_METHOD'] . '<br>';
                    echo 'formhash:' . $_GET['formhash'] . '<br>';
                    echo 'formhash1:' . formhash() . '<br>';
                    echo 'HTTP_X_FLASH_VERSION:' . $_SERVER['HTTP_X_FLASH_VERSION'] . '<br>';
                    echo 'HTTP_REFERER:' . preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) . '<br>';
                    echo 'HTTP_HOST:' . $_SERVER['HTTP_HOST'] . '<br>';
                }
                return FALSE;
            }
        }
    }

}

?>