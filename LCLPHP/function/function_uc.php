<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能： uc整合常用函数
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL')) {
    exit('Access Denied');
}
include_once './config/config_ucenter.php';
include_once './uc_client/client.php';

function uc_login($username, $password, $email = '', $lmg = '') {
    $msg = "";
    list($uid, $username, $password, $email) = uc_user_login($username, $password);
    if ($uid > 0) {
        dsetcookie('uid', $uid);
        dsetcookie('username', $username);
        $ucsynlogin = uc_user_synlogin($uid);
        echo $ucsynlogin;
        return $uid;
    } elseif ($uid == -1) {
        $msg = "用户不存在,或者被删除";
    } elseif ($uid == -2) {
        $msg = "用户密码错误";
    } else {
        $msg = "未定义错误";
    }
    if (strlen($lmg) > 1) {
        return $uid;
    }
    showmessage($msg, 'member.php?mod=logging', 'error');
}

function uc_register($username, $password, $email, $admin = 0) {

    $uid = uc_user_register($username, $password1, $email);
    if ($uid <= 0) {
        $msg = "";
        if ($uid == -1) {
            $msg = '用户名不合法';
        } elseif ($uid == -2) {
            $msg = '包含要允许注册的词语';
        } elseif ($uid == -3) {
            $msg = '用户名已经存在';
        } elseif ($uid == -4) {
            $msg = 'Email 格式有误';
        } elseif ($uid == -5) {
            $msg = 'Email 不允许注册';
        } elseif ($uid == -6) {
            $msg = '该 Email 已经被注册';
        } else {
            $msg = '未知错误';
        }
        if ($admin) {
            cpmsg($msg, 'admin.php?action=contestants&operation=add', 'error');
        } else {
            showmessage($msg, 'member.php?mod=register', 'error');
        }
    } else {
        return $uid;
    }
}

function uc_useredit($username, $oldpw, $newpw) {
    $integer = uc_user_edit($username, $oldpw, $newpw, '');
    if ($integer == 1) {
        return true;
    } elseif ($integer == -1) {
        $msg = "旧密码不正确";
    } elseif ($integer == -4) {
        $msg = "Email 格式有误";
    } elseif ($integer == -5) {
        $msg = "Email 不允许注册";
    } elseif ($integer == -6) {
        $msg = "该 Email 已经被注册";
    } elseif ($integer == -8) {
        $msg = "该用户受保护无权限更改";
    } else {
        $msg = "未定义错误";
    }
    showmessage($msg, 'member.php?mod=space&op=changepassword', 'error');
}

?>