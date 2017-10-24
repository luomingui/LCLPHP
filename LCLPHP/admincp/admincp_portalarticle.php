<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：新闻管理
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
$perpage = 10;
$start = ($page - 1) * $perpage;
$wheresql = " 1=1 ";
$mpurl = ADMINSCRIPT . '?action=portalarticle&perpage=' . $perpage . '&operation=' . $operation;
$searchpram = array();

if ($operation == 'search') {
    $searchpram = array(
        'aid' => $_GET['aid'],
        'catid' => $_GET['catid'],
        'uid' => $_GET['uid'],
        'author' => $_GET['author'],
        'title' => $_GET['title'],
        'summary' => $_GET['summary'],
        'content' => $_GET['content'],
        'url' => $_GET['url'],
        'pic' => $_GET['pic'],
        'tag' => $_GET['tag'],
        'status' => $_GET['status'],
        'perpage' => dintval($_GET['perpage']),
    );
    $wheresql = $wheresql . showsosofrom();
}
if ($operation == 'del') {
    $msg = "请选择";
    $page = $_GET['hiddenpage'];
    $perpage = $_GET['hiddenperpage'];
    $optype = $_GET['optype'];
    $ids = $_GET['ids'];
    if ($optype == "del") {
        if (!empty($ids)) {
            for ($i = 0; $i <= count($ids); $i++) {
                if (!is_null($ids[$i])) {
                    C::t('portal_article')->delete_by_uid($ids[$i]);
                }
            }
        }
        $msg = "批量删除成功.";
    } else if ($optype == "move") {
        $catid = empty($_GET['catid']) ? 0 : dintval($_GET['catid']);
        if (!empty($ids) && $catid > 0) {
            for ($i = 0; $i <= count($ids); $i++) {
                if (!is_null($ids[$i])) {
                    C::t('portal_article')->update_by_aid($ids[$i], array('catid' => $catid));
                }
            }
        }
        $msg = "批量移动分类成功.";
    } else if ($optype == "tag") {
        $raceunitid = empty($_GET['raceunitid']) ? 0 : dintval($_GET['raceunitid']);
        if (!empty($ids) && $catid > 0) {
            for ($i = 0; $i <= count($ids); $i++) {
                if (!is_null($ids[$i])) {
                    C::t('portal_article')->update_by_aid($ids[$i], array('tag' => $raceunitid));
                }
            }
        }
        $msg = "批量关联活动新闻成功.";
    }
    cpmsg($msg, 'admin.php?action=portalarticle&perpage=' . $perpage . '&page=' . $page, 'succeed');
} else if ($operation == 'add') {
    $op = "add";
    if (submitcheck("dosubmit")) {
        $arr = post_frm();
        C::t('portal_article')->insert($arr);
        cpmsg('add_succeed', 'admin.php?action=portalarticle&perpage=' . $perpage, 'succeed');
    } else {
        $selectfrom = showselectfrom();
        include cptemplate('news/portal_article_add');
    }
} else if ($operation == 'edit') {
    $op = "edit";
    if (submitcheck("dosubmit")) {
        $arr = post_frm();
        $aid = empty($_POST['aid']) ? "0" : $_POST['aid'];
        C::t('portal_article')->update_by_aid($aid, $arr);
        cpmsg('edit_succeed', 'admin.php?action=portalarticle&perpage=' . $perpage, 'succeed');
    } else {
        $news = show_frm();
        $selectfrom = showselectfrom($news['catid']);
        include cptemplate('news/portal_article_add');
    }
} else {
    $_G['datalist'] = Array();
    $count = C::t('portal_article')->fetch_all_by_sql($wheresql, '', 0, 0, 1);
    if ($count) {
        $repairs = array();
        $query = C::t('portal_article')->fetch_all_by_sql($wheresql, $ordersql, $start, $perpage);
        foreach ($query as $row) {
            $row['dateline'] = dgmdate($row['dateline'], 'Y-m-d H:i:s', '9999', getglobal('setting/dateformat') . ' H:i:s');
            $row['catname'] = getcatname($row['catid']);
            $rdata[] = $row;
        }
        $_G['datalist'] = $rdata;
        $multi = multi($count, $perpage, $page, 'admin.php?action=portalarticle&perpage=' . $perpage);
    }
    $selectfrom = showselectfrom($searchpram['catid']);
    include cptemplate('news/portal_article');
}

function post_frm() {

    $arr = array();
    //$arr['aid'] = empty($_POST['aid']) ? "0" : $_POST['aid'];
    $arr['catid'] = empty($_POST['catid']) ? "0" : $_POST['catid'];
    $arr['uid'] = empty($_POST['uid']) ? "0" : $_POST['uid'];
    $arr['author'] = empty($_POST['author']) ? "" : $_POST['author'];
    $arr['title'] = empty($_POST['title']) ? "" : $_POST['title'];
    $arr['summary'] = empty($_POST['summary']) ? "" : $_POST['summary'];
    $arr['content'] = empty($_POST['content']) ? "" : $_POST['content'];
    $arr['url'] = empty($_POST['url']) ? "" : $_POST['url'];
    $arr['pic'] = empty($_POST['pic']) ? "" : $_POST['pic'];
    $arr['tag'] = empty($_POST['tag']) ? "" : $_POST['tag'];
    $arr['from'] = empty($_POST['from']) ? "" : $_POST['from'];
    $arr['fromurl'] = empty($_POST['fromurl']) ? "" : $_POST['fromurl'];
    $arr['allowcomment'] = empty($_POST['allowcomment']) ? "" : $_POST['allowcomment'];
    $arr['status'] = empty($_POST['status']) ? "0" : $_POST['status'];
    $arr['dateline'] = dintval(time());

    return $arr;
}

function show_frm() {
    global $_G;
    $uinfo = C::t('portal_article')->fetch_by_aid($_GET['aid']);
    return $uinfo;
}

function showsosofrom() {
    global $mpurl;
    $wheresql = "";
    if (strlen($_GET["aid"]) > 0) {
        $wheresql = $wheresql . " and aid='" . $_GET['aid'] . "'";
        $mpurl .= '&aid=' . $_GET['aid'];
    }
    if (strlen($_GET["catid"]) > 0) {
        $wheresql = $wheresql . " and catid='" . $_GET['catid'] . "'";
        $mpurl .= '&catid=' . $_GET['catid'];
    }
    if (strlen($_GET["uid"]) > 0) {
        $wheresql = $wheresql . " and uid='" . $_GET['uid'] . "'";
        $mpurl .= '&uid=' . $_GET['uid'];
    }
    if (strlen($_GET["author"]) > 0) {
        $wheresql = $wheresql . " and author='" . $_GET['author'] . "'";
        $mpurl .= '&author=' . $_GET['author'];
    }
    if (strlen($_GET["title"]) > 0) {
        $wheresql = $wheresql . " and title='" . $_GET['title'] . "'";
        $mpurl .= '&title=' . $_GET['title'];
    }
    if (strlen($_GET["summary"]) > 0) {
        $wheresql = $wheresql . " and summary='" . $_GET['summary'] . "'";
        $mpurl .= '&summary=' . $_GET['summary'];
    }
    if (strlen($_GET["content"]) > 0) {
        $wheresql = $wheresql . " and content='" . $_GET['content'] . "'";
        $mpurl .= '&content=' . $_GET['content'];
    }
    if (strlen($_GET["url"]) > 0) {
        $wheresql = $wheresql . " and url='" . $_GET['url'] . "'";
        $mpurl .= '&url=' . $_GET['url'];
    }
    if (strlen($_GET["pic"]) > 0) {
        $wheresql = $wheresql . " and pic='" . $_GET['pic'] . "'";
        $mpurl .= '&pic=' . $_GET['pic'];
    }
    if (strlen($_GET["tag"]) > 0) {
        $wheresql = $wheresql . " and tag='" . $_GET['tag'] . "'";
        $mpurl .= '&tag=' . $_GET['tag'];
    }
    if (strlen($_GET["status"]) > 0) {
        $wheresql = $wheresql . " and status='" . $_GET['status'] . "'";
        $mpurl .= '&status=' . $_GET['status'];
    }
    if (strlen($_GET["dateline"]) > 0) {
        $wheresql = $wheresql . " and dateline='" . $_GET['dateline'] . "'";
        $mpurl .= '&dateline=' . $_GET['dateline'];
    }
    return $wheresql;
}

function getcatname($catid) {
    $model = C::t('portal_categry')->fetch_by_catid($catid);
    if ($model) {
        return $model['catname'];
    } else {
        return "保密";
    }
}

function showselectfrom($catid = 0) {
    $s = 'selected="selected"';
    $str = '<select id="catid" name="catid" class="ps vm">';
    $categry1 = C::t('portal_categry')->fetch_by_upid(0);
    foreach ($categry1 as $val1) {
        if ($val1['upid'] == 0) {
            $act = $val1['catid'] == $catid ? $s : "";
            $str.='<option ' . $act . ' value="' . $val1['catid'] . '">' . $val1['catname'] . '</option>';
        }
        $categry2 = C::t('portal_categry')->fetch_by_upid($val1['catid']);
        foreach ($categry2 as $val2) {
            if ($val1['catid'] == $val2['upid']) {
                $act = $val2['catid'] == $catid ? $s : "";
                $str.='<option ' . $act . ' value="' . $val2['catid'] . '">|—' . $val2['catname'] . '</option>';
            }
            $categry3 = C::t('portal_categry')->fetch_by_upid($val2['catid']);
            foreach ($categry3 as $val3) {
                if ($val2['catid'] == $val3['upid']) {
                    $act = $val3['catid'] == $catid ? $s : "";
                    $str.='<option ' . $act . ' value="' . $val3['catid'] . '">|——' . $val3['catname'] . '</option>';
                }
            }
        }
    }
    $str.='</select>';
    return $str;
}

