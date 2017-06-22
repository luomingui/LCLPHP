<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：工具
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
$step = 1;
include template('admincp/common/header');
if ($operation == 'updatecache') {
    $step = max(1, intval($_GET['step']));

    if ($step == 1) {
        print '<div class="container" id="cpcontainer">
            <script type="text/JavaScript">parent.document.title = "兴人类俱乐部 管理中心 - 工具 - 更新缓存";
               if(parent.document.getElementById("admincpnav")) parent.document.getElementById("admincpnav").innerHTML="工具&nbsp;&raquo;&nbsp;更新缓存&nbsp;&nbsp;<a target=main title=添加到常用操作 href=admin.php?action=misc&operation=custommenu&do=add&title=>[+]</a>";</script>
        <div class="itemtitle"><h3>更新缓存</h3><ul class="tab1" style="margin-right:10px"></ul><ul class="stepstat"><li class="current" id="step1">1.确认开始</li><li id="step2">2.开始更新</li><li id="step3">3.更新结果</li></ul><ul class="tab1"></ul></div>
          <table class="tb tb2 " id="tips">
        <tr><th class="partition">技巧提示</th></tr>
        <tr>
            <td class="tipsblock" s="1">
                <ul id="tipslis">
                    <li>数据表中存在垃圾数据，影响查询性能和确保数据的完整性</li>
                    <li>数据缓存：更新站点的全部数据缓存</li>
                </ul>
            </td>
        </tr>
    </table>
    <h3>系统提示</h3>
      <div class="infobox">
        <form method="post" action="admin.php?action=tools&operation=updatecache&step=2">
            <input type="hidden" name="formhash" value="' . FORMHASH . '" /><br />
            <h4 class="marginbot normal">
                <input type="checkbox" name="type[]" value="data" id="datacache" class="checkbox" checked />
                <label for="datacache">数据缓存</label>
                <input type="checkbox" name="type[]" value="tpl" id="wastecache" class="checkbox" checked />
                <label for="wastecache">清理无效数据</label>
                <input type="checkbox" name="type[]" value="importdata" id="importdatacache" class="checkbox" />
                <label for="importdatacache">导入旧用户数据</label>
            </h4><br />
            <p class="margintop">
                <input type="submit" class="btn" name="confirmed" value="确定"> &nbsp;
            </p>
        </form><br />
    </div>
</div>';
    } elseif ($step == 2) {
        $type = implode('_', (array) $_GET['type']);
        print '
    <div class="itemtitle">
        <h3>更新缓存</h3>
        <ul class="tab1" style="margin-right:10px"></ul>
        <ul class="stepstat">
            <li id="step1" >1.确认开始</li>
            <li id="step2" class="current">2.开始更新</li>
            <li id="step3">3.更新结果</li>
        </ul>
        <ul class="tab1"></ul>
    </div>
    <table class="tb tb2 " id="tips">
        <tr><th class="partition">技巧提示</th></tr>
        <tr>
            <td class="tipsblock" s="1">
                <ul id="tipslis">
                    <li>数据表中存在垃圾数据，影响查询性能和确保数据的完整性</li>
                    <li>数据缓存：更新站点的全部数据缓存</li>
                </ul>
            </td>
        </tr>
    </table>';

        cpmsg(cplang('tools_updatecache_waiting'), "admin.php?action=tools&operation=updatecache&step=3&formhash=" . FORMHASH . "&type=$type", 'loading', '', FALSE);
    } elseif ($step == 3) {
        //清理数据
        $type = explode('_', $_GET['type']);
        if (in_array('data', $type)) {
            cleardata();
        }
        if (in_array('tpl', $type)) {
            cleartemplatecache();
        }
        if (in_array('importdata', $type)) {
            //imprtdata();
        }
        print '
    <div class="itemtitle">
        <h3>更新缓存</h3>
        <ul class="tab1" style="margin-right:10px"></ul>
        <ul class="stepstat">
            <li id="step1" >1.确认开始</li>
            <li id="step2" >2.开始更新</li>
            <li id="step3" class="current">3.更新结果</li>
        </ul>
        <ul class="tab1"></ul>
    </div>
    <table class="tb tb2 " id="tips">
        <tr><th class="partition">技巧提示</th></tr>
        <tr>
            <td class="tipsblock" s="1">
                <ul id="tipslis">
                    <li>数据表中存在垃圾数据，影响查询性能和确保数据的完整性</li>
                    <li>数据缓存：更新站点的全部数据缓存</li>
                </ul>
            </td>
        </tr>
    </table>';
        cpmsg('update_cache_succeed', 'admin.php?action=tools&operation=updatecache', 'succeed', '', FALSE);
    }
} elseif ($operation == 'fileperms') {
    $step = max(1, intval($_GET['step']));
    if ($step == 1) {
        print '<div class="container" id="cpcontainer">
            <script type="text/JavaScript">parent.document.title = "兴人类俱乐部 管理中心 - 工具 - 文件权限检查";
               if(parent.document.getElementById("admincpnav")) parent.document.getElementById("admincpnav").innerHTML="工具&nbsp;&raquo;&nbsp;文件权限检查&nbsp;&nbsp;<a target=main title=添加到常用操作 href=admin.php?action=misc&operation=custommenu&do=add&title=>[+]</a>";</script>
        <div class="itemtitle"><h3>文件权限检查</h3>
        <ul class="tab1" style="margin-right:10px"></ul><ul class="stepstat">
        <li class="current" id="step1">1.确认开始</li><li id="step2">2.开始检查</li><li id="step3">3.检查结果</li></ul><ul class="tab1"></ul></div>
        <h3>系统提示</h3><div class="infobox"><br />
        <h4 class="marginbot normal">主要检查文件及文件夹的写入权限点击下面按钮开始进行检查</h4>
        <br />
        <form method="post" action="admin.php?action=tools&operation=fileperms&step=2">
        <input type="hidden" name="formhash" value="' . FORMHASH . '" /><br />
            <p class="margintop">
                <input type="submit" class="btn" name="confirmed" value="确定"> &nbsp;
            </p>
        </form>
        </div></div>';
    } elseif ($step == 2) {
        cpmsg('正在进行文件权限检查，请稍候......', 'admin.php?action=tools&operation=fileperms&step=3', 'loading', '', FALSE);
    } elseif ($step == 3) {

        print '<div class="container" id="cpcontainer">
            <script type="text/JavaScript">parent.document.title = "兴人类俱乐部 管理中心 - 工具 - 文件权限检查";
               if(parent.document.getElementById("admincpnav")) parent.document.getElementById("admincpnav").innerHTML="工具&nbsp;&raquo;&nbsp;文件权限检查&nbsp;&nbsp;<a target=main title=添加到常用操作 href=admin.php?action=misc&operation=custommenu&do=add&title=>[+]</a>";</script>
        <div class="itemtitle"><h3>文件权限检查</h3>
        <ul class="tab1" style="margin-right:10px"></ul><ul class="stepstat">
        <li  id="step1">1.确认开始</li><li id="step2">2.开始检查</li><li id="step3" class="current">3.检查结果</li></ul><ul class="tab1"></ul></div>
        <table class="tb tb2 " id="tips">
     <tr><th  class="partition">技巧提示</th></tr>
     <tr><td class="tipsblock" s="1"><ul id="tipslis"><li>如果某个文件或目录被检查到“无法写入”(以红色列出)，请即刻通过 FTP 或其他工具修改其属性(例如设置为 777)，以确保站点功能的正常使用。</li></ul></td></tr></table>
' . file_perms() . '</div>';
    }
} else if ($operation == 'filecheck') {

}

function cleardata() {
    C::t('ztepy')->delete_by_uid(0);
    $units = C::t('raceunit')->fetch_all();
    foreach ($units as $unit) {
        $unitid = $unit['id'];
        $query = DB::fetch_all('SELECT * FROM lcl_myunit where raceunitid=' . $unitid);
        for ($num = 1; $num < count($query); $num++) {
            $myunitid = $query[$num]['id'];
            DB::query('UPDATE lcl_myunit set score1=' . $num . ' where id=' . $myunitid . ' ');
        }
    }
}

function imprtdata() {
    $rdata = DB::query('SELECT * FROM dz_plugin_zteholding');
    while ($row = DB::fetch($rdata)) {
        if ($row['uid'] > 0) {
            $user = C::t('ztepy')->fetch_by_uid($row['uid']);
            if (!$user) {
                $indata = array(
                    'uid' => $row['uid'],
                    'username' => getdzusername($row['uid']),
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'idcard' => $row['IdCard'],
                    'password' => '',
                    'sex' => $row['sex'],
                    'infosource' => $row['infoSource'],
                    'studno' => '',
                    'province' => $row['province'],
                    'school' => $row['school'],
                    'faculties' => '',
                    'major' => '',
                    'grade' => $row['grade'],
                    'graduyear' => $row['graduYear'],
                    'phone' => $row['phone'],
                    'address' => '',
                    'remarks' => '',
                    'dateline' => strtotime($row['dateline']),
                    'isdelete' => '0',
                );
                C::t('ztepy')->insert($indata);
            }
        }
    }
}

function getdzusername($uid) {
    $user = DB::fetch_first("SELECT * FROM dz_common_member where uid=%d", array($uid));
    if ($user) {
        return $user['username'];
    } else {
        return '';
    }
}

//文件权限检查
function file_perms() {
    $entryarray = array(
        'data',
        'data/cache',
        'data/log',
        'data/template',
        'data/attachment',
        'data/attachment/activity',
    );
    $fileperms_unwritable = "fileperms_unwritable";
    foreach ($entryarray as $entry) {
        $fullentry = LCL_ROOT . './' . $entry;
        if (!is_dir($fullentry) && !file_exists($fullentry)) {
            //continue;
            mkdir($fullentry);
        }
        if (!dir_writeable($fullentry)) {
            $result .= '<li class="error">' . (is_dir($fullentry) ? $lang['dir'] : $lang['file']) . "$entry 无法写入</li>";
        }
    }
    $result = $result ? $result : '<li>文件及目录属性全部正确</li>';
    return '<div class="colorbox"><ul class="fileperms">' . $result . '</ul></div>';
}

