<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：系统设置
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

$setting = C::t('common_setting')->fetch_all(null);

if (!submitcheck('settingsubmit')) {
    if ($operation == 'basic') {
        include cptemplate('global/setting');
    } elseif ($operation == 'datetime') {
        include cptemplate('global/setting_datetime');
    } elseif ($operation == 'mail') {
        include cptemplate('global/setting_mail');
    } elseif ($operation == 'memory') {

        $do_clear_ok = $do == 'clear' ? cplang('setting_memory_do_clear') : '';
        $do_clear_link = '<a href="' . ADMINSCRIPT . '?action=setting&operation=memory&do=clear">' . cplang('setting_memory_clear') . '</a>' . $do_clear_ok;


        $cache_extension = C::memory()->extension;
        $cache_config = C::memory()->config;
        $cache_type = C::memory()->type;

        $redis = array('Redis',
            $cache_extension['redis'] ? cplang('setting_memory_php_enable') : cplang('setting_memory_php_disable'),
            $cache_config['redis']['server'] ? cplang('open') : cplang('closed'),
            $cache_type == 'redis' ? $do_clear_link : '--'
        );

        $memcache = array('memcache',
            $cache_extension['memcache'] ? cplang('setting_memory_php_enable') : cplang('setting_memory_php_disable'),
            $cache_config['memcache']['server'] ? cplang('open') : cplang('closed'),
            $cache_type == 'memcache' ? $do_clear_link : '--'
        );
        $apc = array('APC',
            $cache_extension['apc'] ? cplang('setting_memory_php_enable') : cplang('setting_memory_php_disable'),
            $cache_config['apc'] ? cplang('open') : cplang('closed'),
            $cache_type == 'apc' ? $do_clear_link : '--'
        );
        $xcache = array('Xcache',
            $cache_extension['xcache'] ? cplang('setting_memory_php_enable') : cplang('setting_memory_php_disable'),
            $cache_config['xcache'] ? cplang('open') : cplang('closed'),
            $cache_type == 'xcache' ? $do_clear_link : '--'
        );
        $ea = array('eAccelerator',
            $cache_extension['eaccelerator'] ? cplang('setting_memory_php_enable') : cplang('setting_memory_php_disable'),
            $cache_config['eaccelerator'] ? cplang('open') : cplang('closed'),
            $cache_type == 'eaccelerator' ? $do_clear_link : '--'
        );
        $wincache = array('wincache',
            $cache_extension['wincache'] ? cplang('setting_memory_php_enable') : cplang('setting_memory_php_disable'),
            $cache_config['wincache'] ? cplang('open') : cplang('closed'),
            $cache_type == 'wincache' ? $do_clear_link : '--'
        );
        if ($do == 'clear') {
            C::memory()->clear();
        }
        include cptemplate('global/setting_memory');
    }
} else {
    $settingnew = $_GET['settingnew'];
    $updatecache = FALSE;
    $settings = array();
    foreach ($settingnew as $key => $val) {
        if ($setting[$key] != $val) {
            $updatecache = TRUE;

            $settings[$key] = $val;
        }
    }

    //debug($settings);
    if ($settings) {
        C::t('common_setting')->update_batch($settings);
    }
    cpmsg('setting_update_succeed', ADMINSCRIPT . '?action=setting&operation=' . $operation . (!empty($_GET['anchor']) ? '&anchor=' . $_GET['anchor'] : '') . (!empty($from) ? '&from=' . $from : ''), 'succeed');
}





