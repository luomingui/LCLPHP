<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：Ajax 杂项
 * +----------------------------------------------------------------------
 */
define('CURSCRIPT', 'ajax');
loadcore();
$action = NULL;

$action = !in_array($_GET['action'], array('changeTableVal', 'get_category', 'getRegion', 'ajaxgetRegion', 'clipper')) ? '' : $_GET['action'];

if ($action == "changeTableVal") {
    /**
     * ajax 修改指定表数据字段  一般修改状态 比如 是否推荐 是否开启 等 图标切换的
     * table,id_name,id_value,field,value
     */
    $table = $_GET['table']; // 表名
    $id_name = $_GET['id_name']; // 表主键id名
    $id_value = $_GET['id_value']; // 表主键id值
    $field = $_GET['field']; // 修改哪个字段
    $value = $_GET['value']; // 修改字段值

    $sql = "update " . DB::table($table) . " SET `" . $field . "`=" . $value . " where `" . $id_name . "`=" . $id_value . " ";
    DB::query($sql);
} elseif ($action == "get_category") {
    $pid = $_GET['parent_id']; // 表主键id值
    $list = DB::fetch_all('select id,name FROM ' . DB::table('nop_goods_category') . '  where parent_id=' . $pid);
    foreach ($list as $k => $v)
        $html .= "<option value='{$v['id']}'>{$v['name']}</option>";
    exit($html);
} elseif ($action == "getRegion") {
    getRegion();
} elseif ($action == "ajaxgetRegion") {
    echo json_encode(ajaxgetRegion());
} elseif ($action == "clipper") {
    $title = "未能解析标题";
    $url = $_GET["url"];
    $hasHighPriority = $_GET["highPriority"] == "1";
    if ($url) {
        $url_data = clippernews($url);
        if ($url_data) {
            $newdata = array(
                'catid' => 1,
                'uid' => 1,
                'title' => $url_data['title'],
                'content' => $url_data['content'],
                'url' => $url,
                'status' => 0,
                'dateline' => TIMESTAMP,
            );

            C::t('portal_article')->insert($newdata);
        }
        echo "_UF_.done({success: true,msg: 'save successfully'})";
    } else {
        echo "_UF_.done({success: true,msg: 'Save failed'})";
    }
    exit;
} elseif ($action == 'recommend') {
    $page = empty($_GET['page']) ? 1 : intval($_GET['page']);
    $perpage = empty($_GET['perpage']) ? 10 : intval($_GET['perpage']);
    $curpage = ($page - 1) * $perpage;
    $json = fun_recommend($curpage, $perpage);
    header('Content-type: text/json; charset=UTF-8');
    echo json_encode($json);
    exit;
} else {
    echo "not action " . $action;
}

function loadcore() {
    if (!class_exists('DB')) {
        require_once './class/class_core.php'; //根目录下
        C::app()->init();
    }
}

/*
 * 获取地区
 */

function getRegion() {
    $parent_id = empty($_GET['parent_id']) ? 0 : $_GET['parent_id'];
    $data = C::t('common_district')->fetch_all_by_upid($parent_id);
    $html = '';
    if ($data) {
        foreach ($data as $h) {
            $html .= "<option value='{$h['id']}'>{$h['name']}</option>";
        }
    }
    exit($html);
}

function ajaxgetRegion() {
    $data = C::t('common_district')->fetch_all_by_upid(0);
    $ip_location = array();
    $city_location = array();
    foreach ($data as $row) {
        $c = C::t('common_district')->fetch_all_by_upid($row['id']);

        $ip_location[$row['name']] = array('id' => $row['id'], 'root' => 0, 'djd' => 1, 'c' => $c['id']);
        $city_location[$row['id']] = $c;
    }
    $res = array(
        'ip_location' => $ip_location,
        'city_location' => $city_location
    );
    return $res;
}

function clippernews($url) {
    include_once './tools/readability.php';
    $content = file_get_contents($url);
    $encode = mb_detect_encoding($content, array("ASCII", "UTF-8", "GB2312", "GBK", "BIG5"));
    $r = new Readability($content, $encode);
    $ret = $r->getContent();

    //print_r($ret);
    return $ret;
}

function fun_recommend($page, $perpage) {
    $list = array();

	$rs=  C::t('portal_article')->fetch_all_by_sql(null,'',$page, $perpage);
    while ($rw = DB::fetch($rs)) {
        $rw['authoravatar'] = "uc_server/avatar.php?uid=" . $rw['authorid'] . "&size=middle";
        $rw['url'] = "forum.php?mod=viewthread&tid=" . $rw['tid'];
        $rw['forumurl'] = "forum.php?mod=forumdisplay&fid=" . $rw['fid'];
        $rw['vip'] = "1";
        $rw['summary'] = "版本的目前使用范围已经不仅仅限于幻灯片制作，更可以很方便的实现网站局部左右切换，通过提供的操作接口可以很方便的随时添加页面或者删除页面，实现在平台上的页面滑动";
        $rw['imgattach'] ="";
        $list[] = $rw;
    }
    return $list;
}
?>