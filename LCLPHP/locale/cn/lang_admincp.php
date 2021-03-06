<?php

if (!defined('IN_LCL')) {
    exit('Access Denied');
}

$lang = array
    (
    'header_index' => '首页',
    'header_global' => '全局',
    'header_activity' => '活动',
    'header_news' => '新闻',
    'header_tools' => '工具',
    'header_extended' => '运营',
    'header_founder' => '站长',
    'header_user' => '用户',
    'header_product' => '商品',
    'menu_home' => '管理中心首页',
    'menu_custommenu_manage' => '常用操作管理',
    'menu_setting_basic' => '站点信息',
    'menu_nop_goods_category' => '商品分类',
    'menu_nop_goods' => '商品列表',
    'menu_nop_brand' => '品牌列表',
    'menu_nop_goods_attribute' => '商品属性',
    'menu_nop_goods_type' => '商品类型',
    'menu_nop_spec' => '商品规格',
    'menu_hold_activityactivity' => '活动管理',
    'menu_hold_activitymember' => '参赛人员',
    'menu_hold_team' => '团队管理',
    'menu_hold_nav_expscore' => '专家评分',
    'menu_hold_expscore_team' => '团队评分',
    'menu_hold_nav_stat' => '统计',
    'menu_hold_nav_group' => '团队',
    'menu_hold_statuser' => '用户排行',
    'menu_hold_statteam' => '团队排行',
    'menu_hold_teamlevel' => '团队等级',
    'menu_hold_attachment' => '附件管理',
    'menu_news_categry' => '频道栏目',
    'menu_news' => '文章管理',
    'menu_tools_updatecaches' => '更新缓存',
    'menu_tools_fileperms' => '文件权限检查',
    'menu_tools_logs' => '运行记录',
    'menu_tools_testdata' => '生成测试数据',
    'menu_misc_help' => '站点帮助',
    'menu_misc_cron' => '计划任务',
    'menu_founder' => '站点信息',
    'menu_founder_perm' => '后台管理团队',
    'menu_founder_groupperm' => '编辑团队职务权限 - {group}',
    'menu_founder_permgrouplist' => '编辑权限 - {perm}',
    'menu_founder_memberperm' => '编辑团队成员 - {username}',
    'menu_patch' => '安全中心',
    'menu_upgrade' => '在线升级',
    'menu_optimizer' => '优化大师',
    'menu_security' => '安全大师',
    'menu_db' => '数据库',
    'menu_postsplit' => '帖子分表',
    'menu_threadsplit' => '主题分表',
    'menu_membersplit' => '用户表优化',
    'menu_logs' => '运行记录',
    'menu_custommenu_manage' => '常用操作管理',
    'menu_misc_cron' => '计划任务',
    'menu_setting_mail' => '邮件设置',
    'menu_members_add' => '添加用户',
    'menu_members_edit' => '用户管理',
    'menu_members_newsletter' => '发送通知',
    'menu_members_mobile' => '发送手机通知',
    'menu_usertag' => '用户标签',
    'menu_members_edit_ban_user' => '禁止用户',
    'menu_members_ipban' => '禁止 IP',
    'menu_members_credits' => '积分奖惩',
    'menu_members_profile' => '用户栏目',
    'menu_members_profile_group' => '用户栏目分组',
    'menu_members_verify_setting' => '认证设置',
    'menu_members_stat' => '资料统计',
    'menu_moderate_modmembers' => '审核用户',
    'menu_profilefields' => '用户栏目定制',
    'menu_admingroups' => '管理组',
    'menu_usergroups' => '用户组',
    'menu_follow' => '推荐关注',
    'menu_defaultuser' => '推荐好友',
    'menu_misc_announce' => '站点公告',
    'menu_misc_link' => '友情链接',
    'menu_medals' => '勋章中心',
    'menu_misc_help' => '站点帮助',
    'menu_ec' => '电子商务',
    //menu
    'founder_perm_member_update_succeed' => '管理团队成员资料已更新成功 ',
    'menu_setting_datetime' => '时间设置',
    'menu_setting_district' => '地区设置',
    'menu_setting_optimize' => '内存优化',
    'setting_memory_status' => '当前内存工作状态',
    'setting_memory_state_interface' => '内存接口',
    'setting_memory_state_extension' => 'PHP 扩展环境',
    'setting_memory_state_config' => 'Config 设置',
    'setting_memory_php_enable' => '支持',
    'setting_memory_php_disable' => '不支持',
    'setting_memory_clear' => '内存清理',
    'setting_memory_do_clear' => ' (清理完毕)',
    // end menu
    'open' => '打开',
    'closed' => '关闭',
    'add' => '添加',
    'edit' => '修改',
    'delete' => '删除',
    'login' => '登录',
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
    'displaysearchbox' => '显示搜索框',
    'hidesearchbox' => '显示搜索框',
    'add_succeed' => '添加成功',
    'edit_succeed' => '修改成功',
    'delete_succeed' => '删除成功',
    'login_succeed' => '登录成功',
    'operation' => '操作',
    'administrationcenter' => '管理中心',
    'list' => '列表',
    'option' => '选项',
    'chkall' => '全选',
    'likesearch' => '*表示支持模糊查询',
    'resultranking' => '结果排序',
    'defaultsort' => '默认排序',
    'orderdesc' => '递减',
    'orderasc' => '递增',
    'optionpage' => '每页显示',
    'single' => '个',
    'batchremove' => '批量删除',
    'batchremove_tip' => '删除选中的数据,不可恢复请慎重',
    'update' => '更新',
    'update_tip' => '更新选中的数据',
    'submit' => '提交',
    'notnull' => '不能为空',
    'categoryname' => '分类名称',
    'showall' => '全部展开',
    'hideall' => '全部折叠',
    'succeed' => '成功',
    //end common
    'lcl_message' => '系统提示',
    'message_redirect' => '如果您的浏览器没有自动跳转，请点击这里',
    'message_download' => '如果您的浏览器没有自动下载，请点击此链接',
    'message_return' => '点击这里返回上一页',
    'message_login' => '请登录',
    'apply_succeed' => '报名成功',
    'login_succeed' => '登录成功',
    'addteam_succeed' => '组建团队成功',
    'jointeam_succeed' => '加入团队成功',
    'register_succeed' => '注册会员成功',
    'register_error' => '注册会员失败',
    'error_end_message' => '<a href="http://{host}">{host}</a> 已经将此出错信息详细记录, 由此给您带来的访问不便我们深感歉意',
    'error_end_message' => '<a href="http://{host}">{host}</a> 已经将此出错信息详细记录, 由此给您带来的访问不便我们深感歉意',
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
    'portalcategory_edit_succeed' => '频道栏目编辑成功 ',
    'portalcategory_update_succeed' => '更新分类成功 ',
    'founder_perm_setting' => '基本权限',
    'founder_perm_allowpost' => '<span title="设置成员能修改设置的内容，否则只能浏览后台信息，不能设置。">开启授权</span>',
    'founder_perm_group_update_succeed' => '管理团队职务资料更新成功 ',
    'founder_perm_groupperm_update_succeed' => '职务权限更新成功 ',
    'founder_perm_member_duplicate' => '用户 {name} 已经存在更改',
    'founder_perm_gperm_update_succeed' => '管理团队权限资料更新成功 ',
    'founder_perm_notifyusers_succeed' => '管理通知接收者设置成功 ',
    'district_level_1' => '-省份-',
    'district_level_2' => '-城市-',
    'district_level_3' => '-州县-',
    'district_level_4' => '-乡镇-',
    //message
    'addsubcategory' => '添加子栏目',
    'addthirdcategory' => '添加三级栏目',
    'category_level_1' => '一级分类',
    'category_level_2' => '二级分类',
    'category_level_3' => '三级分类',
    'category_level_4' => '四级分类',
    'category_level_5' => '五级分类',
    'htm_select' => '请选择',
    'reset_password' => '重置密码',
    'usergroups_member' => '会员用户组',
    'usergroups_special' => '自定义用户组',
    'usergroups_specialadmin' => '自定义管理组',
    'usergroups_system' => '系统用户组',
    'usergroups_system_0' => '普通用户',
    'usergroups_system_1' => '管理员',
    'usergroups_system_2' => '超级版主',
    'usergroups_system_3' => '版主',
    'usergroups_system_4' => '禁止发言',
    'usergroups_system_5' => '禁止访问',
    'usergroups_system_6' => '用户IP被禁止',
    'usergroups_system_7' => '游客',
    'usergroups_system_8' => '等待验证',
    'usergroups_creditsrange' => '积分介于',
    'perm' => '权限',
    'plugin' => '插件',
    'inbuilt' => '内置',
    'custom' => '自定义',
    'misc_cron' => '计划任务',
    'misc_cron_list' => '计划任务列表',
    'misc_cron_tips' => '<li>计划任务是系统提供的一项使系统在规定时间自动执行某些特定任务的功能，在需要的情况下，您也可以方便的将其用于站点功能的扩展。</li><li>计划任务是与系统核心紧密关联的功能特性，不当的设置可能造成站点功能的隐患，严重时可能导致站点无法正常运行，因此请务必仅在您对计划任务特性十分了解，并明确知道正在做什么、有什么样后果的时候才自行添加或修改任务项目。</li><li>此处和其他功能不同，本功能中完全按照站点系统默认时差对时间进行设定和显示，而不会依据某一用户或管理员的时差设定而改变显示或设置的时间值。</li>',
    'misc_cron_permonth' => '每月',
    'misc_cron_perweek' => '每周',
    'misc_cron_perday' => '每日',
    'misc_cron_perhour' => '每小时',
    'misc_cron_minute' => '分',
    'misc_cron_hour' => '时',
    'misc_cron_day' => '日',
    'misc_cron_week_day_0' => '日',
    'misc_cron_week_day_1' => '一',
    'misc_cron_week_day_2' => '二',
    'misc_cron_week_day_3' => '三',
    'misc_cron_week_day_4' => '四',
    'misc_cron_week_day_5' => '五',
    'misc_cron_week_day_6' => '六',
    'misc_cron_last_run' => '上次执行时间',
    'misc_cron_next_run' => '下次执行时间',
    'misc_cron_run' => '执行',
    'misc_cron_edit' => '编辑计划任务',
    'misc_cron_edit_tips' => '<li>您正在对系统内置的计划任务进行编辑，除非非常了解 Discuz! 结构，否则强烈建议不要修改默认设置。</li><li>请在修改之前记录原有设置，不当的设置将可能导致站点出现不可预期的错误。</li>',
    'misc_cron_edit_weekday' => '每周',
    'misc_cron_edit_weekday_comment' => '设置星期几执行本任务，“*”为不限制，本设置会覆盖下面的“日”设定',
    'misc_cron_edit_day' => '每月',
    'misc_cron_edit_day_comment' => '设置哪一日执行本任务，“*”为不限制',
    'misc_cron_edit_hour' => '小时',
    'misc_cron_edit_hour_comment' => '设置哪一小时执行本任务，“*”为不限制',
    'misc_cron_edit_minute' => '分钟',
    'misc_cron_edit_minute_comment' => '设置哪些分钟执行本任务，至多可以设置 12 个分钟值，多个值之间用半角逗号 "," 隔开，留空为不限制',
    'misc_cron_edit_filename' => '任务脚本',
    'misc_cron_edit_filename_comment' => '设置本任务的执行程序文件名，请勿包含路径，系统计划任务位于 source/include/cron/ 目录中，插件计划任务位于 source/plugin/插件目录/cron/ 目录中',
    'cron_not_found' => '计划任务未找到',
    'crons_filename_illegal' => '您输入的任务脚本文件名包含非法字符',
    'crons_filename_invalid' => '您指定的任务脚本文件({cronfile})不存在或包含语法错误',
    'crons_time_invalid' => '您没有指定计划任务执行的时间或条件',
    'crons_run_invalid' => '您要运行任务的脚本文件({cronfile})不存在或包含语法错误，任务无法运行',
    'crons_run_succeed' => '计划任务执行成功 ',
    'alipay_not_contract' => '请输入支付宝签约用户信息',
    'alipay_succeed' => '支付宝功能设定成功 ',
    'tenpay_bargainor_invalid' => '请输入 10 位数字的财付通商户号',
    'tenpay_key_invalid' => '密钥只能用32位字母或数字组成',
    'tenpay_succeed' => '财付通功能设定成功 ',
    'orders_disabled' => '您没有启用交易积分或支付宝积分充值功能，无法对订单进行管理',
    'ecommerce_invalidcredit' => '信用度必须大于 0 才能进行评级',
    'ecommerce_must_larger' => '信用等级 {rank} 的信用度必须大于上一等级的信用度',
    'ec_credit_succeed' => '信用评价体系设定成功 ',
);

$lang_db = array();
@include LCL_LOCALE . './lang_db.php';
$lang = array_merge($lang, $lang_db);
?>