<?php 
 
/** 
 * +---------------------------------------------------------------------- 
 * | LCLPHP [ This is a freeware ] 
 * +---------------------------------------------------------------------- 
 * | Copyright (c) 2019 All rights reserved. 
 * +---------------------------------------------------------------------- 
 * | Author: luomingui <e-mail:minguiluo@163.com> <QQ:34178394> 
 * +---------------------------------------------------------------------- 
 * | filefunctio：lcl_common_comment 
 * +---------------------------------------------------------------------- 
 */ 
if (!defined('IN_LCL') || !defined('IN_ADMINCP')) { 
    exit('Access Denied'); 
} 
$perpage = 10; 
$start = ($page - 1) * $perpage; 
$wheresql = " 1=1 "; 
$mpurl = ADMINSCRIPT . '?action=comment&perpage=' . $perpage . '&operation=' . $operation; 
$searchpram = array(); 
 
if ($operation == 'search') { 
    $searchpram = array( 
        'cid' => $_GET['cid'], 
        'uid' => $_GET['uid'], 
        'username' => $_GET['username'], 
        'id' => $_GET['id'], 
        'idtype' => $_GET['idtype'], 
        'postip' => $_GET['postip'], 
        'status' => $_GET['status'], 
        'message' => $_GET['message'], 
    ); 
    $wheresql = $wheresql . showsosofrom(); 
} 
if ($operation == 'del') { 
    $msg = ""; 
    $page = $_GET['hiddenpage']; 
    $perpage = $_GET['hiddenperpage']; 
    $optype = $_GET['optype']; 
    $ids = $_GET['ids']; 
    if ($optype == "del") { 
        if (!empty($ids)) { 
            for ($i = 0; $i <= count($ids); $i++) { 
                if (!is_null($ids[$i])) { 
                    C::t('common_comment')->delete_by_uid($ids[$i]); 
                } 
            } 
        } 
        $msg = cplang('delete_succeed'); 
    } else if ($optype == "outer") { 
        $msg = ""; 
    } 
    cpmsg($msg, 'admin.php?action=comment&perpage=' . $perpage . '&page=' . $page, 'succeed'); 
} else if ($operation == 'add') { 
    $op = "add"; 
    if (submitcheck("dosubmit")) { 
        $arr = post_frm(); 
        C::t('common_comment')->insert($arr); 
        cpmsg('add_succeed', 'admin.php?action=comment&perpage=' . $perpage, 'succeed'); 
    } else { 
        include cptemplate('common_comment_add'); 
    } 
} else if ($operation == 'edit') { 
    $op = "edit"; 
    if (submitcheck("dosubmit")) { 
 
        $arr = post_frm(); 
        $cid = empty($_POST['cid']) ? "0" : $_POST['cid']; 
        C::t('common_comment')->update_by_cid($cid, $arr); 
 
        cpmsg('edit_succeed', 'admin.php?action=comment&perpage=' . $perpage, 'succeed'); 
    } else { 
        $form = show_frm(); 
        include cptemplate('common_comment_add'); 
    } 
} else { 
    $_G['datalist'] = Array(); 
    $count = C::t('common_comment')->fetch_all_by_sql($wheresql, '', 0, 0, 1); 
    if ($count) { 
        $rdata = array(); 
        $query = C::t('common_comment')->fetch_all_by_sql($wheresql, $ordersql, $start, $perpage); 
        foreach ($query as $row) { 
            $row['deadline'] = dgmdate($row['deadline'], 'Y-m-d H:i:s', '9999', getglobal('setting/dateformat') . ' H:i:s'); 
            $rdata[] = $row; 
        } 
        $_G['datalist'] = $rdata; 
        $multi = multi($count, $perpage, $page, 'admin.php?action=comment&perpage=' . $perpage); 
    } 
    include cptemplate('common_comment'); 
} 
 
function post_frm() { 
 
    $arr = array(); 
    $arr['cid'] = empty($_POST['cid']) ? "" : $_POST['cid']; 
    $arr['uid'] = empty($_POST['uid']) ? "" : $_POST['uid']; 
    $arr['username'] = empty($_POST['username']) ? "" : $_POST['username']; 
    $arr['id'] = empty($_POST['id']) ? "" : $_POST['id']; 
    $arr['idtype'] = empty($_POST['idtype']) ? "" : $_POST['idtype']; 
    $arr['postip'] = empty($_POST['postip']) ? "" : $_POST['postip']; 
    $arr['status'] = empty($_POST['status']) ? "" : $_POST['status']; 
    $arr['message'] = empty($_POST['message']) ? "" : $_POST['message']; 
 
    return $arr; 
} 
 
function show_frm() { 
    global $_G; 
    $uinfo = C::t('common_comment')->fetch_by_cid($_GET['cid']); 
    return $uinfo; 
} 
 
function showsosofrom() { 
    global $mpurl; 
    $wheresql = ""; 
    if (strlen($_GET["cid"]) > 0) { 
        $wheresql = $wheresql . " and cid='" . $_GET['cid'] . "'"; 
        $mpurl .= '&cid=' . $_GET['cid']; 
    } 
    if (strlen($_GET["uid"]) > 0) { 
        $wheresql = $wheresql . " and uid='" . $_GET['uid'] . "'"; 
        $mpurl .= '&uid=' . $_GET['uid']; 
    } 
    if (strlen($_GET["username"]) > 0) { 
        $wheresql = $wheresql . " and username='" . $_GET['username'] . "'"; 
        $mpurl .= '&username=' . $_GET['username']; 
    } 
    if (strlen($_GET["id"]) > 0) { 
        $wheresql = $wheresql . " and id='" . $_GET['id'] . "'"; 
        $mpurl .= '&id=' . $_GET['id']; 
    } 
    if (strlen($_GET["idtype"]) > 0) { 
        $wheresql = $wheresql . " and idtype='" . $_GET['idtype'] . "'"; 
        $mpurl .= '&idtype=' . $_GET['idtype']; 
    } 
    if (strlen($_GET["postip"]) > 0) { 
        $wheresql = $wheresql . " and postip='" . $_GET['postip'] . "'"; 
        $mpurl .= '&postip=' . $_GET['postip']; 
    } 
    if (strlen($_GET["dateline"]) > 0) { 
        $wheresql = $wheresql . " and dateline='" . $_GET['dateline'] . "'"; 
        $mpurl .= '&dateline=' . $_GET['dateline']; 
    } 
    if (strlen($_GET["status"]) > 0) { 
        $wheresql = $wheresql . " and status='" . $_GET['status'] . "'"; 
        $mpurl .= '&status=' . $_GET['status']; 
    } 
    if (strlen($_GET["message"]) > 0) { 
        $wheresql = $wheresql . " and message='" . $_GET['message'] . "'"; 
        $mpurl .= '&message=' . $_GET['message']; 
    } 
    return $wheresql; 
} 
 

