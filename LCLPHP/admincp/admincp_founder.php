<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：后台管理团队
 * +----------------------------------------------------------------------
 */
if (empty($admincp) || !is_object($admincp)) {
    exit('Access Denied');
}
if ($operation == 'perm') {
    $do = !in_array(getgpc('do'), array('group', 'member', 'gperm', 'notifyusers')) ? 'member' : getgpc('do');
    if ($do == 'group') {
        $id = intval(getgpc('id'));

        if ($id) {
            if (!submitcheck('submit')) {
                $cpgroupname = ($cpgroup = C::t('admincp_group')->fetch($id)) ? $cpgroup['cpgroupname'] : '';
                $html_table = htmltable($id);
                include cptemplate('founder/perm');
            } else {
                C::t('admincp_perm')->delete_by_cpgroupid_perm($id);
                if ($_GET['permnew']) {
                    foreach ($_GET['permnew'] as $perm) {
                        C::t('admincp_perm')->insert(array('cpgroupid' => $id, 'perm' => $perm));
                    }
                }
                cpmsg('founder_perm_groupperm_update_succeed', 'admin.php?action=founder&operation=perm&do=group', 'succeed');
            }
        } else {
            if (!submitcheck('submit')) {
                $groups = C::t('admincp_group')->fetch_all();
                include cptemplate('founder/group');
            } else {
                if (!empty($_GET['newcpgroupname'])) {
                    if (C::t('admincp_group')->fetch_by_cpgroupname($_GET['newcpgroupname'])) {
                        cpmsg('founder_perm_group_name_duplicate', '', 'error', array('name' => $_GET['newcpgroupname']));
                    }
                    C::t('admincp_group')->insert(array('cpgroupname' => strip_tags($_GET['newcpgroupname'])));
                }
                if (!empty($_GET['delete'])) {
                    C::t('admincp_perm')->delete_by_cpgroupid_perm($_GET['delete']);
                    C::t('admincp_member')->update_cpgroupid_by_cpgroupid($_GET['delete'], array('cpgroupid' => 0));
                    C::t('admincp_group')->delete($_GET['delete']);
                }
                if (!empty($_GET['name'])) {
                    foreach ($_GET['name'] as $id => $name) {
                        if ($groups[$id] != $name) {
                            $cpgroupid = ($cpgroup = C::t('admincp_group')->fetch_by_cpgroupname($name)) ? $cpgroup['cpgroupid'] : 0;
                            if ($cpgroupid && $_GET['name'][$cpgroupid] == $groups[$cpgroupid]) {
                                cpmsg('founder_perm_group_name_duplicate', '', 'error', array('name' => $name));
                            }
                            C::t('admincp_group')->update($id, array('cpgroupname' => $name));
                        }
                    }
                }
                cpmsg('founder_perm_group_update_succeed', 'admin.php?action=founder&operation=perm&do=group', 'succeed');
            }
        }
    } elseif ($do == 'member') {
        $id = empty($_GET['id']) ? 0 : $_GET['id'];
        if (!$id) {
            if (!submitcheck('submit')) {
                $groups = C::t('admincp_group')->fetch_all();
                $membersdb = C::t('admincp_member')->fetch_all();
                $members = array();
                foreach ($membersdb as $row) {
                    $model = C::t('admincp_group')->fetch_by_cpgroupid($row['cpgroupid']);
                    if ($model) {
                        $row['cpgroupname'] = $model['cpgroupname'];
                    }
                    $members[] = $row;
                }
                include cptemplate('founder/member');
            } else {
                if (!empty($_GET['newcpusername'])) {
                    C::t('admincp_member')->insert(array('cpgroupid' => $_GET['newcpgroupid'],
                        'username' => $_GET['newcpusername'],
                        'password' => '123456',
                        'dateline' => TIMESTAMP
                    ));
                }
                if (!empty($_GET['delete'])) {
                    C::t('admincp_member')->delete($_GET['delete']);
                }
                cpmsg('founder_perm_member_update_succeed', 'admin.php?action=founder&operation=perm&do=member', 'succeed');
            }
        } else {
            if (!submitcheck('submit')) {

            } else {

            }
        }
    }
}

function getactionarray() {
    require './admincp/admincp_menu.php';
    unset($topmenu['index'], $menu['index']);
    $actioncat = $actionarray = array();
    $actioncat[] = 'setting';
    $actioncat = array_merge($actioncat, array_keys($topmenu));
    $actionarray['setting'][] = array('founder_perm_allowpost', '_allowpost');
    foreach ($menu as $tkey => $items) {
        foreach ($items as $item) {
            $actionarray[$tkey][] = $item;
        }
    }
    return array('actions' => $actionarray, 'cats' => $actioncat);
}

function htmltable($id) {
    $perms = array();
    foreach (C::t('admincp_perm')->fetch_all_by_cpgroupid($id) as $perm) {
        $perms[] = $perm['perm'];
    }
    $data = getactionarray();
    $htable = "";
    foreach ($data['cats'] as $topkey) {
        if (!$data['actions'][$topkey]) {
            continue;
        }
        $checkedall = true;
        $row = '<tr><td class="vtop" id="perms_' . $topkey . '">';
        foreach ($data['actions'][$topkey] as $k => $item) {
            if (!$item) {
                continue;
            }
            $checked = @in_array($item[1], $perms);
            if (!$checked) {
                $checkedall = false;
            }
            $row .= $item[1] ? '<div class="item' . ($checked ? ' checked' : '') . '"><a class="right" title="' . cplang('config') . '" href="#" >&nbsp;</a><label class="txt"><input name="permnew[]" value="' . $item[1] . '" class="checkbox" type="checkbox" ' . ($checked ? 'checked="checked" ' : '') . ' onclick="checkclk(this)" />' . cplang($item[0]) . '</label></div>' : '';
        }
        $row .= '</td></tr>';
        if ($topkey != 'setting') {
            $title = '<tr><th colspan="15" class="partition"><label><input class="checkbox" type="checkbox" onclick="permcheckall(this, \'perms_' . $topkey . '\')" ' . ($checkedall ? 'checked="checked" ' : '') . '/> ' . cplang('header_' . $topkey) . '</label></th></tr>';
        } else {
            $title = ' <tr><th colspan="15" class="partition">基本权限</th></tr>';
        }
        $htable.= $title . $row;
    }
    return $htable;
}

