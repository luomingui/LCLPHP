<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：常用操作管理
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
if ($operation == 'custommenu') {
    if (!$do) {
        if (!submitcheck('optionsubmit')) {
            $_G['datalist'] = Array();
            $num = C::t('admincp_cmenu')->count_by_uid($_G['adminid']);
            $multipage = multi($num, $perpage, $start, ADMINSCRIPT . '?action=misc&operation=custommenu');

            $query = C::t('admincp_cmenu')->fetch_all_by_uid($_G['adminid'], $start, $perpage);
            $rdata = array();
            foreach ($query as $row) {
                $rdata[] = $row;
            }
            $_G['datalist'] = $rdata;

            include cptemplate('global/misc');
        } else {
            if ($ids = dimplode($_GET['delete'])) {
                C::t('admincp_cmenu')->delete($_GET['delete'], $_G['adminid']);
            }
            if (is_array($_GET['titlenew'])) {
                foreach ($_GET['titlenew'] as $id => $title) {
                    $_GET['urlnew'][$id] = rawurlencode($_GET['urlnew'][$id]);
                    $title = dhtmlspecialchars($_GET['langnew'][$id] && cplang($_GET['langnew'][$id], false) ? $_GET['langnew'][$id] : $title);
                    $ordernew = intval($_GET['displayordernew'][$id]);
                    C::t('admincp_cmenu')->update($id, array('title' => $title, 'displayorder' => $ordernew, 'url' => dhtmlspecialchars($_GET['urlnew'][$id])));
                }
            }

            if (is_array($_GET['newtitle'])) {
                foreach ($_GET['newtitle'] as $k => $v) {
                    $_GET['urlnew'][$k] = rawurlencode($_GET['urlnew'][$k]);
                    C::t('admincp_cmenu')->insert(array(
                        'title' => dhtmlspecialchars($v),
                        'url' => dhtmlspecialchars($_GET['newurl'][$k]),
                        'displayorder' => intval($_GET['newdisplayorder'][$k]),
                        'sort' => 1,
                        'uid' => $_G['adminid'],
                        'dateline' => dintval(time()),
                    ));
                }
            }
            cpmsg('custommenu_edit_succeed', ADMINSCRIPT . '?action=misc&operation=custommenu', 'succeed');
        }
    } elseif ($do == 'add') {

        if ($_GET['title'] && $_GET['url']) {
            C::t('admincp_cmenu')->insert(array(
                'title' => dhtmlspecialchars($_GET['title']),
                'url' => dhtmlspecialchars($_GET['url']),
                'displayorder' => 0,
                'sort' => 1,
                'uid' => $_G['adminid'],
                'dateline' => dintval(time()),
            ));
            cpmsg('custommenu_add_succeed', rawurldecode($_GET['url']), 'succeed', array('title' => cplang($_GET['title'])));
        } else {
            cpmsg('parameters_error', '', 'error');
        }
    }
} elseif ($operation == 'cron') {
    if (empty($_GET['edit']) && empty($_GET['run'])) {
        if (!submitcheck('cronssubmit')) {

            $query = DB::query("SELECT * FROM " . DB::table('common_cron') . " ORDER BY type DESC");
            $_G['datalist'] = array();
            while ($cron = DB::fetch($query)) {
                $disabled = $cron['weekday'] == -1 && $cron['day'] == -1 && $cron['hour'] == -1 && $cron['minute'] == '' ? 'disabled' : '';

                if ($cron['day'] > 0 && $cron['day'] < 32) {
                    $cron['time'] = cplang('misc_cron_permonth') . $cron['day'] . cplang('misc_cron_day');
                } elseif ($cron['weekday'] >= 0 && $cron['weekday'] < 7) {
                    $cron['time'] = cplang('misc_cron_perweek') . cplang('misc_cron_week_day_' . $cron['weekday']);
                } elseif ($cron['hour'] >= 0 && $cron['hour'] < 24) {
                    $cron['time'] = cplang('misc_cron_perday');
                } else {
                    $cron['time'] = cplang('misc_cron_perhour');
                }

                $cron['time'] .= $cron['hour'] >= 0 && $cron['hour'] < 24 ? sprintf('%02d', $cron[hour]) . cplang('misc_cron_hour') : '';

                if (!in_array($cron['minute'], array(-1, ''))) {
                    foreach ($cron['minute'] = explode("\t", $cron['minute']) as $k => $v) {
                        $cron['minute'][$k] = sprintf('%02d', $v);
                    }
                    $cron['minute'] = implode(',', $cron['minute']);
                    $cron['time'] .= $cron['minute'] . cplang('misc_cron_minute');
                } else {
                    $cron['time'] .= '00' . cplang('misc_cron_minute');
                }

                $cron['lastrun'] = $cron['lastrun'] ? dgmdate($cron['lastrun'], $_G['setting']['dateformat'] . "<\b\\r />" . $_G['setting']['timeformat']) : '<b>N/A</b>';
                $cron['nextcolor'] = $cron['nextrun'] && $cron['nextrun'] + $_G['setting']['timeoffset'] * 3600 < TIMESTAMP ? 'style="color: #ff0000"' : '';
                $cron['nextrun'] = $cron['nextrun'] ? dgmdate($cron['nextrun'], $_G['setting']['dateformat'] . "<\b\\r />" . $_G['setting']['timeformat']) : '<b>N/A</b>';
                $cron['run'] = $cron['available'];
                $efile = explode(':', $cron['filename']);
                if (count($efile) > 1 && !in_array($efile[0], $_G['setting']['plugins']['available'])) {
                    $cron['run'] = 0;
                }
                $cron['ctype'] = cplang($cron['type'] == 'system' ? 'inbuilt' : ($cron['type'] == 'plugin' ? 'plugin' : 'custom'));

                $_G['datalist'][] = $cron;
            }
            include cptemplate('global/misc_cron');
        } else {
            if ($ids = dimplode($_GET['delete'])) {
                DB::delete('common_cron', "cronid IN ($ids) AND type='user'");
            }

            if (is_array($_GET['namenew'])) {
                foreach ($_GET['namenew'] as $id => $name) {
                    $newcron = array(
                        'name' => dhtmlspecialchars($_GET['namenew'][$id]),
                        'available' => $_GET['availablenew'][$id]
                    );
                    if (empty($_GET['availablenew'][$id])) {
                        $newcron['nextrun'] = '0';
                    }
                    DB::update('common_cron', $newcron, "cronid='$id'");
                }
            }

            if ($newname = trim($_GET['newname'])) {
                DB::insert('common_cron', array(
                    'name' => dhtmlspecialchars($newname),
                    'type' => 'user',
                    'available' => '0',
                    'weekday' => '-1',
                    'day' => '-1',
                    'hour' => '-1',
                    'minute' => '',
                    'nextrun' => $_G['timestamp'],
                ));
            }

            $query = DB::query("SELECT cronid, filename FROM " . DB::table('common_cron'));
            while ($cron = DB::fetch($query)) {
                $efile = explode(':', $cron['filename']);
                $pluginid = '';
                if (count($efile) > 1 && ispluginkey($efile[0])) {
                    $pluginid = $efile[0];
                    $cron['filename'] = $efile[1];
                }
                if (!$pluginid) {
                    if (!file_exists(LCL_ROOT . './include/cron/' . $cron['filename'])) {
                        DB::update('common_cron', array(
                            'available' => '0',
                            'nextrun' => '0',
                                ), "cronid='$cron[cronid]'");
                    }
                } else {
                    if (!file_exists(LCL_ROOT . './plugin/' . $pluginid . '/cron/' . $cron['filename'])) {
                        DB::delete('common_cron', "cronid='$cron[cronid]'");
                    }
                }
            }

            updatecache('setting');
            cpmsg('crons_succeed', 'admin.php?action=misc&operation=cron', 'succeed');
        }
    } else {
        $cronid = empty($_GET['run']) ? $_GET['edit'] : $_GET['run'];
        $cron = DB::fetch_first("SELECT * FROM " . DB::table('common_cron') . " WHERE cronid='$cronid'");
        if (!$cron) {
            cpmsg('cron_not_found', '', 'error');
        }
        $cron['filename'] = str_replace(array('..', '/', '\\'), array('', '', ''), $cron['filename']);
        $cronminute = str_replace("\t", ',', $cron['minute']);
        $cron['minute'] = explode("\t", $cron['minute']);


        if (!empty($_GET['edit'])) {

            if (!submitcheck('editsubmit')) {

                $weekdayselect = $dayselect = $hourselect = '';
                for ($i = 0; $i <= 6; $i++) {
                    $weekdayselect .= "<option value=\"$i\" " . ($cron['weekday'] == $i ? 'selected' : '') . ">" . $lang['misc_cron_week_day_' . $i] . "</option>";
                }

                for ($i = 1; $i <= 31; $i++) {
                    $dayselect .= "<option value=\"$i\" " . ($cron['day'] == $i ? 'selected' : '') . ">$i $lang[misc_cron_day]</option>";
                }

                for ($i = 0; $i <= 23; $i++) {
                    $hourselect .= "<option value=\"$i\" " . ($cron['hour'] == $i ? 'selected' : '') . ">$i $lang[misc_cron_hour]</option>";
                }

                include cptemplate('global/misc_cron_add');
            } else {

                $daynew = $_GET['weekdaynew'] != -1 ? -1 : $_GET['daynew'];
                if (strpos($_GET['minutenew'], ',') !== FALSE) {
                    $minutenew = explode(',', $_GET['minutenew']);
                    foreach ($minutenew as $key => $val) {
                        $minutenew[$key] = $val = intval($val);
                        if ($val < 0 || $var > 59) {
                            unset($minutenew[$key]);
                        }
                    }
                    $minutenew = array_slice(array_unique($minutenew), 0, 12);
                    $minutenew = implode("\t", $minutenew);
                } else {
                    $minutenew = intval($_GET['minutenew']);
                    $minutenew = $minutenew >= 0 && $minutenew < 60 ? $minutenew : '';
                }

                $efile = explode(':', $_GET['filenamenew']);
                if (substr($_GET['filenamenew'], -4) !== '.php') {
                    cpmsg('crons_filename_illegal', '', 'error');
                }

                $pluginid = '';
                if (count($efile) > 1 && ispluginkey($efile[0])) {
                    $pluginid = $efile[0];
                    $_GET['filenamenew'] = $efile[1];
                }

                if (!$pluginid) {
                    if (preg_match("/[\\\\\/\:\*\?\"\<\>\|]+/", $_GET['filenamenew'])) {
                        cpmsg('crons_filename_illegal', 'admin.php?action=misc&operation=cron', 'error');
                        exit();
                    } elseif (!is_readable(LCL_ROOT . ($cronfile = "./include/cron/{$_GET['filenamenew']}"))) {
                        cpmsg('crons_filename_invalid', 'admin.php?action=misc&operation=cron', 'error', array('cronfile' => $cronfile));
                        exit();
                    } elseif ($_GET['weekdaynew'] == -1 && $daynew == -1 && $_GET['hournew'] == -1 && $minutenew === '') {
                        cpmsg('crons_time_invalid', '', 'error');
                        exit();
                    }
                } else {
                    if (preg_match("/[\\\\\/\:\*\?\"\<\>\|]+/", $_GET['filenamenew'])) {
                        cpmsg('crons_filename_illegal', 'admin.php?action=misc&operation=cron', 'error');
                        exit();
                    } elseif (!is_readable(LCL_ROOT . ($cronfile = "./plugin/$pluginid/cron/{$_GET['filenamenew']}"))) {
                        cpmsg('crons_filename_invalid', 'admin.php?action=misc&operation=cron', 'error', array('cronfile' => $cronfile));
                        exit();
                    } elseif ($_GET['weekdaynew'] == -1 && $daynew == -1 && $_GET['hournew'] == -1 && $minutenew === '') {
                        cpmsg('crons_time_invalid', 'admin.php?action=misc&operation=cron', 'error');
                        exit();
                    }
                    $_GET['filenamenew'] = $pluginid . ':' . $_GET['filenamenew'];
                }

                DB::update('common_cron', array(
                    'weekday' => $_GET['weekdaynew'],
                    'day' => $daynew,
                    'hour' => $_GET['hournew'],
                    'minute' => $minutenew,
                    'filename' => trim($_GET['filenamenew']),
                        ), "cronid='$cronid'");

                lcl_cron::run($cronid);

                cpmsg('crons_succeed', 'admin.php?action=misc&operation=cron', 'succeed');
            }
        } else {

            $efile = explode(':', $cron['filename']);
            if (count($efile) > 1 && ispluginkey($efile[0])) {
                $cronfile = LCL_ROOT . './plugin/' . $efile[0] . '/cron/' . $efile[1];
            } else {
                $cronfile = LCL_ROOT . "./include/cron/$cron[filename]";
            }

            if (substr($cronfile, -4) !== '.php' || !file_exists($cronfile)) {
                cpmsg('crons_run_invalid', 'admin.php?action=misc&operation=cron', 'error', array('cronfile' => $cronfile));
            } else {
                discuz_cron::run($cron['cronid']);
                cpmsg('crons_run_succeed', 'admin.php?action=misc&operation=cron', 'succeed');
            }
        }
    }
}


