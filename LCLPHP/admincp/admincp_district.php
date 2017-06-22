<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：地区设置
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
$values = array(intval($_GET['pid']), intval($_GET['cid']), intval($_GET['did']));
$elems = array($_GET['province'], $_GET['city'], $_GET['district']);
$level = 1;
$upids = array(0);
$theid = 0;
for ($i = 0; $i < 3; $i++) {
    if (!empty($values[$i])) {
        $theid = intval($values[$i]);
        $upids[] = $theid;
        $level++;
    } else {
        for ($j = $i; $j < 3; $j++) {
            $values[$j] = '';
        }
        break;
    }
}
if (submitcheck('editsubmit')) {
    $delids = array();
    foreach (C::t('common_district')->fetch_all_by_upid($theid) as $value) {
        $usetype = 0;
        if ($_POST['birthcity'][$value['id']] && $_POST['residecity'][$value['id']]) {
            $usetype = 3;
        } elseif ($_POST['birthcity'][$value['id']]) {
            $usetype = 1;
        } elseif ($_POST['residecity'][$value['id']]) {
            $usetype = 2;
        }
        if (!isset($_POST['district'][$value['id']])) {
            $delids[] = $value['id'];
        } elseif ($_POST['district'][$value['id']] != $value['name'] || $_POST['displayorder'][$value['id']] != $value['displayorder'] || $usetype != $value['usetype']) {
            C::t('common_district')->update($value['id'], array('name' => $_POST['district'][$value['id']], 'displayorder' => $_POST['displayorder'][$value['id']], 'usetype' => $usetype));
        }
    }
    if ($delids) {
        $ids = $delids;
        for ($i = $level; $i < 4; $i++) {
            $ids = array();
            foreach (C::t('common_district')->fetch_all_by_upid($delids) as $value) {
                $value['id'] = intval($value['id']);
                $delids[] = $value['id'];
                $ids[] = $value['id'];
            }
            if (empty($ids)) {
                break;
            }
        }
        C::t('common_district')->delete($delids);
    }
    if (!empty($_POST['districtnew'])) {
        $inserts = array();
        $displayorder = '';
        foreach ($_POST['districtnew'] as $key => $value) {
            $displayorder = trim($_POST['districtnew_order'][$key]);
            $value = trim($value);
            if (!empty($value)) {
                C::t('common_district')->insert(array('name' => $value, 'level' => $level, 'upid' => $theid, 'displayorder' => $displayorder));
            }
        }
    }
    cpmsg('setting_district_edit_success', 'admin.php?action=district&pid=' . $values[0] . '&cid=' . $values[1] . '&did=' . $values[2], 'succeed');
} else {
    $options = array(1 => array(), 2 => array(), 3 => array());
    $thevalues = array();
    foreach (C::t('common_district')->fetch_all_by_upid($upids) as $value) {
        $options[$value['level']][] = array($value['id'], $value['name']);
        if ($value['upid'] == $theid) {
            $thevalues[] = array($value['id'], $value['name'], $value['displayorder'], $value['usetype']);
        }
    }

    $names = array('province', 'city', 'district');
    for ($i = 0; $i < 3; $i++) {
        $elems[$i] = !empty($elems[$i]) ? $elems[$i] : $names[$i];
    }

    $html = '';
    for ($i = 0; $i < 3; $i++) {
        $l = $i + 1;
        $jscall = ($i == 0 ? 'this.form.city.value=\'\';this.form.district.value=\'\';' : '') . "refreshdistrict('$elems[0]', '$elems[1]', '$elems[2]')";
        $html .= '<select name="' . $elems[$i] . '" id="' . $elems[$i] . '" onchange="' . $jscall . '">';
        $html .= '<option value="">' . lang('admincp', 'district_level_' . $l) . '</option>';
        foreach ($options[$l] as $option) {
            $selected = $option[0] == $values[$i] ? ' selected="selected"' : '';
            $html .= '<option value="' . $option[0] . '"' . $selected . '>' . $option[1] . '</option>';
        }
        $html .= '</select>&nbsp;&nbsp;';
    }

    include cptemplate('global/district');
}
?>