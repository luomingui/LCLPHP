<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: lang_admincp.php 35926 2016-05-11 02:21:11Z nemohou $
 */
if (!defined('IN_LCL')) {
    exit('Access Denied');
}


$lang = array
    (
    'lcl_message' => '系统提示',
    'message_redirect' => '如果您的浏览器没有自动跳转，请点击这里',
    'message_download' => '如果您的浏览器没有自动下载，请点击此链接',
    'message_return' => '点击这里返回上一页',
    'message_login' => '请登录',
    'add_succeed' => '添加成功',
    'edit_succeed' => '修改成功',
    'delete_succeed' => '删除成功',
    'login_succeed' => '登录成功',
    'apply_succeed' => '报名成功',
    'login_succeed' => '登录成功',
    'addteam_succeed' => '组建团队成功',
    'jointeam_succeed' => '加入团队成功',
    'register_succeed' => '注册会员成功',
    'register_error' => '注册会员失败',
    'error_end_message' => '<a href="http://{host}">{host}</a> 已经将此出错信息详细记录, 由此给您带来的访问不便我们深感歉意',
    'error_end_message' => '<a href="http://{host}">{host}</a> 已经将此出错信息详细记录, 由此给您带来的访问不便我们深感歉意',
    'nextpage' => '下一页',
    'prevpage' => '上一页',
    'pageunit' => '页',
    'total' => '共',
    '10k' => '万',
    'pagejumptip' => '输入页码，按回车快速跳转',
    'date' => array(
        'before' => '前',
        'day' => '天',
        'yday' => '昨天',
        'byday' => '前天',
        'hour' => '小时',
        'half' => '半',
        'min' => '分钟',
        'sec' => '秒',
        'now' => '刚刚',
    ),
    'yes' => '是',
    'no' => '否',
    'weeks' => array(
        1 => '周一',
        2 => '周二',
        3 => '周三',
        4 => '周四',
        5 => '周五',
        6 => '周六',
        7 => '周日',
    ),
    'dot' => '、',
    'archive' => '存档',
    'portal' => '门户',
    'end' => '末尾',
    'faq' => '帮助',
    'search' => '搜索',
    'page' => '第{page}页',
    'close' => '关闭',
    'tools_updatecache_data' => '更新缓存',
    'tools_updatecache_tpl' => '清理无效数据',
    'update_cache_succeed' => '全部缓存更新完毕',
    'tools_updatecache_waiting' => '正在更新缓存，请稍候......',
    'custommenu_edit_succeed' => '常用操作成功更新',
    'custommenu_add' => '添加常用操作',
    'custommenu_addto' => '添加到常用操作',
    'custommenu_add_succeed' => '菜单 {title} 已成功添加到常用操作，即将返回上一页，您可以<a href="{ADMINSCRIPT}?action=misc&operation=custommenu">点这里编辑常用操作</a>',
    'setting_basic' => '站点信息',
    'setting_basic_bbname' => '站点名称',
    'setting_basic_bbname_comment' => '站点名称，将显示在浏览器窗口标题等位置',
    'setting_basic_sitename' => '网站名称',
    'setting_basic_sitename_comment' => '网站名称，将显示在页面底部的联系方式处',
    'setting_basic_siteurl' => '网站 URL',
    'setting_basic_siteurl_comment' => '网站 URL，将作为链接显示在页面底部',
    'setting_basic_adminemail' => '管理员邮箱',
    'setting_basic_adminemail_comment' => '管理员 E-mail，将作为系统发邮件的时候的发件人地址',
    'setting_basic_index_name' => '首页文件名',
    'setting_basic_index_name_comment' => '设置站点首页的文件名，默认为“forum.php?mod=index”，如果您更改了此设置，那么您需要使用“FTP工具”手动重命名文件名称',
    'setting_basic_site_qq' => 'QQ在线客服号码',
    'setting_basic_site_qq_comment' => '<a href="" onclick="this.href=\'http://wp.qq.com/set.html?from=discuz&uin=\'+$(\'settingnew[site_qq]\').value" target="_blank">设置我的QQ在线状态</a>',
    'setting_basic_icp' => '网站备案信息代码',
    'setting_basic_icp_comment' => '页面底部可以显示 ICP 备案信息，如果网站已备案，在此输入您的授权码，它将显示在页面底部，如果没有请留空',
    'setting_basic_stat' => '网站第三方统计代码',
    'setting_basic_stat_comment' => '页面底部可以显示第三方统计',
    'setting_basic_boardlicensed' => '显示授权信息链接',
    'setting_basic_boardlicensed_comment' => '选择“是”将在页脚显示商业授权用户链接，链接将指向 Discuz! 官方网站，用户可通过此链接验证其所使用的 Discuz! 是否经过商业授权',
    'setting_basic_bbclosed' => '关闭站点',
    'setting_basic_bbclosed_comment' => '暂时将站点关闭，其他人无法访问，但不影响管理员访问',
    'setting_basic_closedreason' => '关闭站点的原因',
    'setting_basic_closedreason_comment' => '站点关闭时出现的提示信息',
    'setting_basic_bbclosed_activation' => '站点关闭时允许 UCenter 中的用户激活',
    'setting_update_succeed' => '当前设置更新成功 ',
    'parameters_error' => '参数错误',
);

$adminextend = array();
if (file_exists($adminextendfile = LCL_ROOT . './data/sysdata/cache_adminextend.php')) {
    @include $adminextendfile;
    foreach ($adminextend as $extend) {
        $extend_lang = array();
        @include LCL_ROOT . './language/lang_default_' . $extend;
        $lang = array_merge($lang, $extend_lang);
    }
}
?>