<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：日志
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
$htmltitle = "系统错误";
$htmlcontext = "无";
$multipage = '';
$lpp = empty($_GET['lpp']) ? 15 : $_GET['lpp'];
$operation = in_array($operation, array('illegal', 'ban', 'cp', 'error', 'sendmail')) ? $operation : 'error';
$logdir = LCL_ROOT . './data/log/';
$logfiles = get_log_files($logdir, $operation . ($operation == 'sendmail' ? '' : 'log'));
$logs = array();
$lastkey = count($logfiles) - 1;
$lastlog = $logfiles[$lastkey];
krsort($logfiles);
if ($logfiles) {
    if (!isset($_GET['day']) || strexists($_GET['day'], '_')) {
        list($_GET['day'], $_GET['num']) = explode('_', $_GET['day']);
        $logs = file(($_GET['day'] ? $logdir . $_GET['day'] . '_' . $operation . ($operation == 'sendmail' ? '' : 'log') . ($_GET['num'] ? '_' . $_GET['num'] : '') . '.php' : $logdir . $lastlog));
    } else {
        $logs = file($logdir . $_GET['day'] . '_' . $operation . ($operation == 'sendmail' ? '' : 'log') . '.php');
    }
}
$start = ($page - 1) * $lpp;
$logs = array_reverse($logs);

$num = count($logs);
$multipage = multi($num, $lpp, $page, ADMINSCRIPT . "?action=logs&operation=$operation&lpp=$lpp" . (!empty($_GET['day']) ? '&day=' . $_GET['day'] : ''), 0, 3);
$logs = array_slice($logs, $start, $lpp);

if ($operation == 'illegal') {
    $htmltitle = "密码错误";
} elseif ($operation == 'ban') {
    $htmltitle = "禁止用户";
} elseif ($operation == 'cp') {
    $htmltitle = "后台访问";
    $str = "";
    foreach ($logs as $k => $logrow) {
        $log = explode("\t", $logrow);
        if (empty($log[1])) {
            continue;
        }
        $log[1] = dgmdate($log[1], 'y-n-j H:i');
        $str.= "<tr class='hover'>
                    <td class='bold'>" . $log[2] . "</td>
                    <td>" . $log[4] . "</td>
                    <td>" . $log[1] . "</td>
                    <td>" . $log[5] . "</td>
                    <td><a href='javascript:;' onclick='togglecplog(" . $k . ")'>" . cutstr($log[6], 200) . "</a></td>
                </tr><tbody id='cplog_" . $k . "' style='display:none;'>
                    <tr><td colspan='6'>" . $log[6] . "</td></tr></tbody>";
    }
    $htmlcontext = "<script type='text/javascript'>
function togglecplog(k) {
	var cplogobj = document.getElementById('cplog_'+k);
	if(cplogobj.style.display == 'none') {
		cplogobj.style.display = '';
	} else {
		cplogobj.style.display = 'none';
	}
}
</script><table class='tb tb2 fixpadding' style='table-layout: fixed'>
        <tr class='header'>
            <td class='td23'>操作者</td>
            <td class='td24'>IP 地址</td>
            <td class='td24'>时间</td>
            <td class='td24'>动作</td>
            <td >其他</td>
        </tr>" . $str . "</table>";
} elseif ($operation == 'error') {
    $htmltitle = "系统错误";
    $str = "";
    foreach ($logs as $logrow) {
        $log = explode("\t", $logrow);
        if (empty($log[1])) {
            continue;
        }
        $str.= " <tr>
                <td class='bold'>" . dgmdate($log[1], 'Y-m-d H:i:s') . "</td>
                <td>" . $log[2] . "<br>" . $log[4] . "<br>" . $log[5] . "</td>
            </tr>";
    }
    $htmlcontext = "<table class='tb tb2 fixpadding' style='table-layout: fixed'>
        <tr class='header'><td class='td23'>时间</td><td>内容</td></tr>" . $str . "</table>";
} elseif ($operation == 'sendmail') {
    $htmltitle = "邮件发送失败";
    $logarr = $logemail = array();
    foreach ($logs as $logrow) {
        $log = explode("\t", $logrow);
        if (empty($log[1])) {
            continue;
        }
        $log[5] = trim(str_replace('sendmail failed.', '', $log[5]));
        if (!$log[5]) {
            continue;
        }
        $logemail[] = $log[5];
        $logarr[] = $log;
    }

    $members = C::t('ztepy')->fetch_all_by_email($logemail);
    $str = "";
    foreach ($logarr as $log) {
        $log[6] = $members[$log[5]]['username'];
        if (strtolower($log[6]) == strtolower($_G['username'])) {
            $log[6] = "<b>$log[6]</b>";
        }
        showtablerow('', array('class="smallefont"', 'class="bold"', 'class="smallefont"'), array(
            $log[1],
            '<a href="home.php?mod=space&username=' . $log[6] . '" target="_blank">' . $log[6] . '</a>',
            $log[5]
        ));

        $log[1] = dgmdate($log[1], 'y-n-j H:i');
        $str.= "<tr class='hover'>
                    <td class='bold'>" . $log[1] . "</td>
                    <td>" . $log[6] . "</td>
                    <td>" . $log[5] . "</td>
                </tr>";
    }
    $htmlcontext = "<table class='tb tb2 fixpadding' style='table-layout: fixed'>
        <tr class='header'>
            <td class='td23'>时间</td>
            <td class='td23'>用户名</td>
            <td class='td23'>Email</td>
        </tr>" . $str . "</table>";
}

include cptemplate('logs');

function get_log_files($logdir = '', $action = 'action') {
    $dir = opendir($logdir);
    $files = array();
    while ($entry = readdir($dir)) {
        $files[] = $entry;
    }
    closedir($dir);

    if ($files) {
        sort($files);
        $logfile = $action;
        $logfiles = array();
        $ym = '';
        foreach ($files as $file) {
            if (strpos($file, $logfile) !== FALSE) {
                if (substr($file, 0, 6) != $ym) {
                    $ym = substr($file, 0, 6);
                }
                $logfiles[$ym][] = $file;
            }
        }
        if ($logfiles) {
            $lfs = array();
            foreach ($logfiles as $ym => $lf) {
                $lastlogfile = $lf[0];
                unset($lf[0]);
                $lf[] = $lastlogfile;
                $lfs = array_merge($lfs, $lf);
            }
            return $lfs;
        }
        return array();
    }
    return array();
}

