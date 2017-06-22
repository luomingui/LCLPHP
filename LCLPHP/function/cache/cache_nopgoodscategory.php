<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：商品分类缓存文件
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL')) {
    exit('Access Denied');
}

function build_cache_nopgoodscategory() {
    global $_G;

    $data = C::t('nop_goods_category')->range();
    foreach ($data as $key => $value) {
        $upid = $value['parent_id'];
        if ($upid && isset($data[$upid])) {
            $data[$upid]['children'][] = $key;
            while ($upid && isset($data[$upid])) {
                $upid = $data[$upid]['parent_id'];
            }
        }
    }

    savecache('nopgoodscategory', $data);
}

?>