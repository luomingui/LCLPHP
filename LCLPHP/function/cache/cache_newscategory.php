<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：新闻类型缓存文件
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL')) {
    exit('Access Denied');
}

function build_cache_newscategory() {
    global $_G;

    $data = C::t('portal_categry')->range();
    foreach ($data as $key => $value) {
        $upid = $value['upid'];
        $data[$key]['level'] = 0;
        if ($upid && isset($data[$upid])) {
            $data[$upid]['children'][] = $key;
            while ($upid && isset($data[$upid])) {
                $data[$key]['level'] += 1;
                $upid = $data[$upid]['upid'];
            }
        }
    }

    foreach ($data as $key => &$value) {
        $url = $topid = '';
        $foldername = $value['foldername'];
        if ($value['level']) {
            $topid = $key;
            $foldername = '';
            while ($data[$topid]['upid']) {
                if ($data[$topid]['foldername'] && $data[$key]['foldername']) {
                    $foldername = $data[$topid]['foldername'] . '/' . $foldername;
                }
                $topid = $data[$topid]['upid'];
            }
            if ($foldername)
                $foldername = $data[$topid]['foldername'] . '/' . $foldername;
        } else {
            $topid = $key;
        }
        $value['topid'] = $topid;

        $value['fullfoldername'] = trim($foldername, '/');
    }

    savecache('newscategory', $data);
}

?>