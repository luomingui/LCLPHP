<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：新闻分类
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

$mpurl = ADMINSCRIPT . '?action=news&perpage=' . $perpage . '&operation=' . $operation;
$operation = in_array($operation, array('delete', 'list', 'add', 'edit')) ? $operation : 'list';

loadcache('newscategory');
$newscategory = $_G['cache']['newscategory'];

if ($operation == 'list') {
    if (empty($newscategory) && C::t('portal_categry')->count()) {
        updatecache('newscategory');
        loadcache('newscategory', true);
        $newscategory = $_G['cache']['newscategory'];
    }

    if (!submitcheck('editsubmit')) {
        $html_talbe = htmldatalist();
        include template('admincp/news/portal_categry');
    } else {
        if (is_array($_GET['neworder'])) {
            foreach ($_GET['neworder'] as $id => $title) {
                $ordernew = intval($_GET['neworder'][$id]);
                $catname = $_GET['name'][$id];
                //debug($_GET['name']);
                C::t('portal_categry')->update($id, array('catname' => $catname, 'displayorder' => $ordernew));
            }
        }
        cpmsg('portalcategory_update_succeed', 'admin.php?action=newscategry', 'succeed');
    }
} elseif ($operation == 'delete') {
    $_GET['catid'] = max(0, intval($_GET['catid']));
    if (!$_GET['catid']) {
        cpmsg('portalcategory_catgory_not_found', '', 'error');
    }
    C::t('portal_categry')->delete($_GET['catid']);
    cpmsg('delete_succeed', 'admin.php?action=portal_categry', 'succeed');
} elseif ($operation == 'edit' || $operation == 'add') {
    $_GET['catid'] = intval($_GET['catid']);
    $_GET['upid'] = intval($_GET['upid']);
    if ($operation == 'add') {
        $categryup = C::t('portal_categry')->fetch_by_catid($_GET['upid']);
    } else {
        $categry = C::t('portal_categry')->fetch_by_catid($_GET['catid']);
    }
    if (!submitcheck('detailsubmit')) {
        include template('admincp/news/portal_categry_add');
    } else {
        $_GET['closed'] = intval($_GET['closed']) ? 0 : 1;
        $_GET['catname'] = trim($_GET['catname']);
        $editcat = array(
            'catname' => $_GET['catname'],
            'closed' => $_GET['closed'],
            'displayorder' => intval($_GET['displayorder']),
        );
        if ($operation == 'add') {
            $editcat['upid'] = $_GET['upid'];
            $editcat['dateline'] = TIMESTAMP;
            $editcat['uid'] = $_G['adminid'];
            $_GET['catid'] = C::t('portal_categry')->insert($editcat, true);
        } else if ($operation == 'edit') {
            C::t('portal_categry')->update($_GET['catid'], $editcat);
        }
        $url = $operation == 'add' ? 'admin.php?action=newscategry#cat' . $_GET['catid'] : 'admin.php?action=newscategry&operation=edit&catid=' . $_GET['catid'];
        cpmsg('portalcategory_edit_succeed', $url, 'succeed');
    }
}

function htmldatalist() {
    $html = "";
    $html_child = "";
    $html_child_child = "";
    $datalist = C::t('portal_categry')->fetch_by_upid(0);
    foreach ($datalist as $row) {

        $catid = $row["catid"];
        $catname = $row["catname"];
        $displayorder = $row["displayorder"];
        $row['deadline'] = dgmdate($row['deadline'], 'Y-m-d H:i:s', '9999', getglobal('setting/dateformat') . ' H:i:s');
        $upid = $row['upid'];

        if ($catid > 0) {
            $datalist_child = C::t('portal_categry')->fetch_by_upid($catid);
            if ($datalist_child) {
                foreach ($datalist_child as $row_child) {
                    $catid_child = $row_child["catid"];
                    $datalist_child_child = C::t('portal_categry')->fetch_by_upid($row_child['catid']);
                    if ($datalist_child_child) {
                        foreach ($datalist_child_child as $row_child_child) {
                            $html_child_child .= showcategoryrow($row_child_child, 'lastchildboard');
                        }
                    }
                    $html_child .= showcategoryrow($row_child, 'board', '<a class="addchildboard" href="admin.php?action=newscategry&operation=add&upid=' . $catid_child . '">添加三级频道栏目</a>') . $html_child_child;
                }
            } else {
                $html_child = "";
            }
        }
        $html .= '<tbody><tr class="hover" id="cat' . $catid . '">
                    <td onclick=toggle_group("group_' . $catid . '")><a id="a_group_' . $catid . '" href="javascript:;">[-]</a></td>
                    <td class="td25"><input type="text" class="txt" name="neworder[' . $catid . ']" value="' . $displayorder . '" /></td>
                    <td><div class="parentboard"><input type="text" class="txt" name="name[' . $catid . ']" value="' . $catname . '" /></div></td>
                    <td>
                        <a href="admin.php?action=newscategry&operation=edit&catid=' . $catid . '">编辑</a>&nbsp;
                        <a href="admin.php?action=newscategry&operation=move&catid=' . $catid . '">转移</a>&nbsp;
                        <a href="admin.php?action=newscategry&operation=delete&catid=' . $catid . '">删除</a>&nbsp;
                    </td>
                    <td>
                        <a href="admin.php?action=news&operation=search&&catid=' . $catid . '">管理</a>&nbsp;
                        <a href="admin.php?action=news&operation=add&&catid=' . $catid . '" >发布</a>
                    </td>
                </tr></tdoby><tbody id="group_' . $catid . '">' . $html_child . '</tdoby><tr>
                    <td>&nbsp;</td>
                    <td colspan="9">
                        <div class="lastboard">
                            <a class="addtr" href="admin.php?action=newscategry&operation=add&upid=' . $catid . '">添加频道子栏目</a>
                        </div>
                    </td>
                </tr>';
    }
    return $html;
}

function showcategoryrow($row, $classname, $addurl) {
    $catid_child = $row["catid"];
    $catname_child = $row["catname"];
    $displayorder_child = $row["displayorder"];
    $html_child = ' <tr class="hover" id="cat' . $catid_child . '">
                    <td onclick=toggle_group("group_' . $catid_child . '")></td>
                    <td class="td25"><input type="text" class="txt" name="neworder[' . $catid_child . ']" value="' . $displayorder_child . '" /></td>
                    <td><div class="' . $classname . '"><input type="text" class="txt" name="name[' . $catid_child . ']" value="' . $catname_child . '" />' . $addurl . '</div></td>
                    <td>
                        <a href="admin.php?action=newscategry&operation=edit&catid=' . $catid_child . '">编辑</a>&nbsp;
                        <a href="admin.php?action=newscategry&operation=move&catid=' . $catid_child . '">转移</a>&nbsp;
                        <a href="admin.php?action=newscategry&operation=delete&catid=' . $catid_child . '">删除</a>&nbsp;
                    </td>
                    <td>
                        <a href="admin.php?action=news&operation=search&catid=' . $catid_child . '">管理</a>&nbsp;
                        <a href="admin.php?action=news&operation=add&&catid=' . $catid_child . '" target="_blank">发布</a>
                    </td>
                </tr>';
    return $html_child;
}
