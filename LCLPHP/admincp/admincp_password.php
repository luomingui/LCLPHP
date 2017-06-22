<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：修改密码
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

$nav_title = '修改密码';
if (submitcheck('pwdsubmit')) {
    $uid = $_POST['uid'];
    $oldpassword = $_POST['oldpassword'];
    $password = $_POST['newpassword'];
    $password2 = $_POST['newpassword2'];

    $model = C::t('admincp_member')->fetch_by_uid($uid);
    if ($model) {
        if ($model['password'] == $oldpassword) {
            C::t('admincp_member')->update_by_uid($uid, array(
                'password' => $password2,
                'dateline' => TIMESTAMP,
            ));
            cpmsg('密码修改成功', '', 'succeed');
        } else {
            cpmsg('密码填写错误,请重新登录', '', 'error');
        }
    } else {
        cpmsg('登录过期了,请重新登录', '', 'error');
    }
} else {
    include cptemplate('password');
}
?>