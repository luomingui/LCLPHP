<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：系统设置缓存文件
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL')) {
    exit('Access Denied');
}

function build_cache_setting() {
    global $_G;

    $data = array();
    $skipkeys = array('posttableids', 'mastermobile', 'masterqq', 'masteremail', 'closedreason',
        'creditsnotify', 'backupdir', 'custombackup', 'jswizard', 'maxonlines', 'modreasons', 'newsletter',
        'postno', 'postnocustom', 'customauthorinfo', 'domainwhitelist', 'ipregctrl',
        'ipverifywhite', 'fastsmiley', 'defaultdoing', 'antitheftsetting',
    );
    foreach (C::t('common_setting')->fetch_all_not_key($skipkeys) as $setting) {

        if ($setting['skey'] == 'extcredits') {

        } elseif ($setting['skey'] == 'creditsformula') {

        }
        $_G['setting'][$setting['skey']] = $data[$setting['skey']] = $setting['svalue'];
    }

    include_once LCL_ROOT . './lcl_version.php';
    $_G['setting']['version'] = $data['version'] = LCL_VERSION;

    savecache('setting', $data);
    $_G['setting'] = $data;
}

?>