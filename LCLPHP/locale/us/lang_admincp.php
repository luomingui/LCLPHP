<?php

if (!defined('IN_LCL')) {
    exit('Access Denied');
}


$lang = array
    (
    'header_index' => 'Home',
    'header_global' => 'Global',
    'header_hold' => 'Activity',
    'header_news' => 'News',
    'header_tools' => 'Tools',
    'header_extended' => 'Operation',
    'header_founder' => 'Founder',
    'menu_home' => 'Home',
    'menu_custommenu_manage' => 'Common Operations',
    'menu_setting_basic' => 'Site Information',
    'menu_hold_subject' => 'Activity Manager',
    'menu_hold_contestants' => 'User Manager',
    'menu_hold_team' => 'Team Manager',
    'menu_hold_nav_expscore' => 'Expert Score',
    'menu_hold_expscore_team' => 'Team Score',
    'menu_hold_nav_stat' => 'Statistics',
    'menu_hold_statuser' => 'Use Rankingr',
    'menu_hold_statteam' => 'Team Ranking',
    'menu_news_categry' => 'Categry',
    'menu_news' => 'Article',
    'menu_tools_updatecaches' => 'Update Cache',
    'menu_tools_fileperms' => 'File Perms',
    'menu_tools_logs' => 'Rec Run',
    'menu_tools_testdata' => 'Generate test data',
    'menu_misc_help' => 'Site Help',
    'menu_founder' => 'Site Information',
    'menu_founder_perm' => 'Manager Team',
    'menu_founder_groupperm' => 'The Editorial Team Position - {group}',
    'menu_founder_permgrouplist' => 'Edit Permission - {perm}',
    'menu_founder_memberperm' => 'Editorial Team Members - {username}',
    'menu_patch' => 'Security Center',
    'menu_upgrade' => 'Online Update',
    'menu_optimizer' => 'Vista Master',
    'menu_security' => 'Master Safety',
    'menu_db' => '数据库',
    'menu_postsplit' => '帖子分表',
    'menu_threadsplit' => '主题分表',
    'menu_membersplit' => '用户表优化',
    'menu_logs' => 'Rec Run',
    'menu_custommenu_manage' => '常用操作管理',
    'menu_misc_cron' => '计划任务',
    'menu_setting_mail' => '邮件设置',
    'founder_perm_member_update_succeed' => '管理团队成员资料已更新成功 ',
    'menu_setting_datetime' => 'Timeset',
    'menu_setting_district' => 'Regional Settings',
    'menu_setting_optimize' => 'Memory Optimize',
    // end menu
   
);

$adminextend = array();
if (file_exists($adminextendfile = LCL_ROOT . './data/sysdata/cache_adminextend.php')) {
    @include $adminextendfile;
    foreach ($adminextend as $extend) {
        $extend_lang = array();
        @include LCL_ROOT . './language/lang_admincp_' . $extend;
        $lang = array_merge($lang, $extend_lang);
    }
}
?>