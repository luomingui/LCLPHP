<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：登录页
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

$authkey = md5('luomingui' . $_SERVER['HTTP_USER_AGENT'] . $_G['clientip']);
$rand = rand(100000, 999999);
$seccodeinit = rawurlencode(authcode($rand, 'ENCODE', $authkey, 180));

$nav_title = '登录';
if (submitcheck('loginsubmit')) {

    $seccodehidden = urldecode(getgpc('seccodehidden', 'P'));
    $seccode = strtoupper(getgpc('seccode', 'P'));
    if (!seccode::seccode_check($seccodehidden, $seccode)) {
        //showmessage('验证码输入错误', '', 'succeed');
        echo '<SCRIPT LANGUAGE="javascript"> alert("验证码输入错误！");   </SCRIPT> ';
        include cptemplate('login');
        exit();
    }

    $username = $_POST['admin_username'];
    $password = $_POST['admin_password'];
    $admin = C::t('admincp_member')->fetch_by_login($username, $password);
    if ($admin) {
        dsetcookie('adminid', $admin['uid']);
        dsetcookie('adminname', $admin['username']);
        header("Location: admin.php?action=frame");
    } else {
        echo '<SCRIPT LANGUAGE="javascript"> alert("用户名或密码错误,请重新输入...！");   </SCRIPT> ';
        include cptemplate('login');
        //showmessage('用户名或密码错误,请重新输入...', '', 'succeed');
    }
    exit;
} else {
    include cptemplate('login');
}
?>