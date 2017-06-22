<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2019 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: luomingui <e-mail:minguiluo@163.com> <QQ:34178394>
 * +----------------------------------------------------------------------
 * | filefunctio：lclphp db language
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL')) {
    exit('Access Denied');
}


$lang_db = array
    (
  
    // Table structure for admincp_cmenu
    'admincp_cmenu' => '常用菜单',
    'admincp_cmenu_cmenuid' => '编号',
    'admincp_cmenu_title' => '名称',
    'admincp_cmenu_url' => '地址',
    'admincp_cmenu_sort' => '类型',
    'admincp_cmenu_displayorder' => '显示顺序',
    'admincp_cmenu_clicks' => '点击数',
    'admincp_cmenu_uid' => '添加用户',
    'admincp_cmenu_dateline' => '添加时间',
    // Table structure for admincp_group
    'admincp_group' => '后台管理组',
    'admincp_group_cpgroupid' => '后台组编号',
    'admincp_group_cpgroupname' => '后台组名称',
    // Table structure for admincp_member
    'admincp_member' => '后台用户',
    'admincp_member_uid' => '用户编号',
    'admincp_member_username' => '用户名称',
    'admincp_member_password' => '用户密码',
    'admincp_member_cpgroupid' => '成员组编号',
    'admincp_member_customperm' => '自定义管理权限',
    'admincp_member_dateline' => '添加时间',
    // Table structure for admincp_perm
    'admincp_perm' => '后台组权限',
    'admincp_perm_cpgroupid' => ' 后台组编号',
    'admincp_perm_perm' => '后台组权限',
    // Table structure for common_comment
    'common_comment' => '评论管理',
    'common_comment_cid' => '评论编号',
    'common_comment_uid' => '用户编号',
    'common_comment_username' => '用户名',
    'common_comment_id' => '评论对象编号',
    'common_comment_idtype' => '评论对象类型',
    'common_comment_postip' => '评论IP',
    'common_comment_dateline' => '评论时间',
    'common_comment_status' => '评论状态',
    'common_comment_message' => '评论内容',
    // Table structure for common_district
    'common_district' => '地区管理',
    // Table structure for common_faq
    'common_faq' => '帮助',
    'common_faq_id' => '帮助编号',
    'common_faq_fpid' => '帮助父编号',
    'common_faq_displayorder' => '排序',
    'common_faq_identifier' => '帮助标识',
    'common_faq_keyword' => '帮助关键词',
    'common_faq_title' => '帮助标题',
    'common_faq_message' => '帮助内容',
    // Table structure for common_member
    'common_member' => '用户管理',
    'common_member_uid' => '用户编号',
    'common_member_email' => '用户邮箱',
    'common_member_username' => '用户名',
    'common_member_password' => '密码',
    'common_member_status' => '状态',
    'common_member_emailstatus' => '邮箱是否验证',
    'common_member_avatarstatus' => '是否有头像',
    'common_member_videophotostatus' => '视频认证状态',
    'common_member_adminid' => '管理员编号',
    'common_member_groupid' => '会员组编号',
    'common_member_groupexpiry' => '用户组有效期',
    'common_member_extgroupids' => '扩展用户组',
    'common_member_regdate' => '注册时间',
    'common_member_credits' => '总积分',
    'common_member_notifysound' => '短信声音',
    'common_member_timeoffset' => '时区校正',
    'common_member_newpm' => '新短消息数量',
    'common_member_newprompt' => '新提醒数目',
    'common_member_accessmasks' => '标志',
    'common_member_allowadmincp' => '标志',
    'common_member_onlyacceptfriendpm' => '是否只接收好友短消息',
    'common_member_conisbind' => '用户是否绑定QC',
    'common_member_freeze' => 'freeze',
    // Table structure for common_perm
    'common_perm' => '权限表',
    'common_perm_permid' => '权限编号',
    'common_perm_permname' => '权限名称',
    'common_perm_perm' => '权限',
    'common_perm_type' => '类型',
    // Table structure for common_setting
    'common_setting' => '全局设置',
    'common_setting_skey' => '变量名',
    'common_setting_svalue' => '变量值',
    // Table structure for common_syscache
    'common_syscache' => '系统缓存',
    'common_syscache_cname' => '缓存名称',
    'common_syscache_ctype' => '缓存类型',
    'common_syscache_dateline' => '缓存生成时间',
    'common_syscache_data' => '缓存数据',
    // Table structure for common_usergroup
    'common_usergroup' => '用户组',
    'common_usergroup_groupid' => '会员组编号',
    'common_usergroup_radminid' => '关联关管理组',
    'common_usergroup_type' => ' 类型',
    'common_usergroup_system' => '会员是否可以自由进出',
    'common_usergroup_grouptitle' => '组头衔',
    'common_usergroup_creditshigher' => '该组的积分上限',
    'common_usergroup_creditslower' => '该组的积分下限',
    'common_usergroup_stars' => '星星数量',
    'common_usergroup_color' => '组头衔颜色',
    'common_usergroup_icon' => '用户编号',
    'common_usergroup_allowvisit' => '允许访问',
    'common_usergroup_allowsendpm' => '是否允许发送短信息',
    'common_usergroup_allowinvite' => '允许使用邀请注册',
    'common_usergroup_allowmailinvite' => '允许通过论坛邮件系统发送邀请码',
    'common_usergroup_maxinvitenum' => '最大邀请码拥有数量',
    'common_usergroup_inviteprice' => '邀请码购买价格',
    'common_usergroup_maxinviteday' => '邀请码有效期',
    // Table structure for common_usergroup_perm
    'common_usergroup_perm' => '用户组权限',
    'common_usergroup_perm_groupid' => '会员组编号',
    'common_usergroup_perm_perm' => '权限',
  
    // Table structure for portal_article
    'portal_article' => '文章',
    'portal_article_aid' => '编号',
    'portal_article_catid' => '类别',
    'portal_article_uid' => '添加用户',
    'portal_article_title' => '标题',
    'portal_article_content' => '内容',
    'portal_article_summary' => '摘要',
    'portal_article_author' => '作者',
    'portal_article_from' => '来源',
    'portal_article_fromurl' => '来源URL',
    'portal_article_allowcomment' => '是否允许评论',
    'portal_article_url' => '访问URL',
    'portal_article_pic' => '封面图片',
    'portal_article_tag' => '关键字',
    'portal_article_status' => '状态',
    'portal_article_dateline' => '添加时间',
    // Table structure for portal_categry
    'portal_categry' => '文章分类',
    'portal_categry_catid' => '栏目编号',
    'portal_categry_upid' => '上级栏目编号',
    'portal_categry_catname' => '标题',
    'portal_categry_displayorder' => '显示顺序',
    'portal_categry_closed' => '是否关闭',
    'portal_categry_uid' => '添加用户',
    'portal_categry_dateline' => '添加时间',
);
?>

